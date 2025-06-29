<?php
 
namespace App\Listeners;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\PaddleTransactions;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Access\AccessHandler;
use App\Http\Controllers\Access\PaddlePriceHandler;
use App\Http\Controllers\Access\PaddleTransactionHandler;
use App\Http\Controllers\Access\PaddleSubscriptionHandler;

class PaddleWebhookListener extends Controller
{
    /**
     * Webhookhandling via Paddle Service Provider
     * See Docs: https://developer.paddle.com/webhooks/overview
     * 
     * Webhook Data Handling
     *  > "Controllers\Access"
     *  > "Middleware\PaddleWebhookVerification"
     * 
     * Setup: Webhook Gateway in Paddle Cockpit
     * https://sandbox-vendors.paddle.com/notifications
     *  > Webhook URL: 
     *      > Endpoint: https://{URL}/access/webhook
     *      > See "routes\web.php"
     *  > Set .env Keys 
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        try {
            // Prepare
            $payload = $request->json()->all();
            $contentData = $payload['data'];
            if(empty($contentData)) {
                return response()->json([
                    'message' => 'No data error.',
                ], 422);
            };

            // Handle accoring webhook type
            $paddleStatus = $payload['event_type'] ?? null;
            if ($paddleStatus === 'transaction.completed') {
                $this->initiateUserAccess($contentData);
            } 

            else if ($paddleStatus === 'transaction.payment_failed' || $paddleStatus === 'transaction.canceled') {
                $this->cancelUserAccess($contentData);
            }

            else if ($paddleStatus === 'subscription.updated' || $paddleStatus === 'subscription.canceled') {
                $Subscription = new PaddleSubscriptionHandler();
                $Subscription->updateSubscriptionByWebhook($contentData);
            }

            else if ($paddleStatus === 'price.created' || $paddleStatus === 'price.updated') {
                $Price = new PaddlePriceHandler();
                $Price->updatePriceByWebhook($contentData);
            }
        } catch (Exception $e) {
            Log::channel('webhooks')->error("WebhookHandler error: " . now(), ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Webhook error.',
            ], 422);
        }

        return response()->json([
            'message' => 'Webhook processed.',
        ], 200);
    }

    /**
     * Initialize user access via transaction webhook.
     * 
     ** Type of transactions:
     *  - "One-Time Purchase": The user pays once.
     *      > User access is granted for a one-time period, either based on expiration date or quantity. 
     *  - "Subscription": The user subscribes to a price, which is billed periodically by Paddle. 
     *      > User access is granted on a recurring basis via webhook, linked to the subscription and the corresponding user.
     * 
     ** Logic: User access verification 
     *  1. Client access initialization 
     *      - After client checkout via PaddleJS, the inital user-access-request must be stored in DB to verify subsequent webhook calls     
     *          >  See "\Controllers\Access\UserAccessController::initializeClientCheckout()"
     *  2. Verify user access
     *      - Paddle fires a webhook call, after new registered transaction
     *          > Verify transactions and grand user access according transaction_token or subscription_token
     *              - transaction_token does exists: Price has been purchased very recently by client
     *              - transaction_token does not exists: Transaction must be initialized by a subscription
     *          > Grant access to referred user, according price access
     *              - $access_token: feature access 
     *              - $expiration_period: access limit (deadline)
     *              - $quantity: access limit (amount of something)
     * 
     * @param array $contentData
     * @return void
     */
    private function initiateUserAccess(array $contentData)
    {
        try {
            $PaddleTransaction = new PaddleTransactionHandler(
                PaddleTransactions::where([
                    'transaction_token' => $contentData['id']
                ])->first()
            );

            // Already verified
            if($PaddleTransaction->transaction?->access_added) 
                return;

            // If no transaction is found
            // Webhook is initialized via subscription by some user
            $PaddleTransaction->setTransactionAccessAttributes($contentData);
            if(!$PaddleTransaction->transaction && $PaddleTransaction->subscription_token) {
                $PaddleTransaction->initializeUserTransactionBySubscription(
                    $PaddleTransaction->subscription_token,
                    'webhook.subscription.transaction'
                );
            }

            // If transaction is found
            // Entry has been initialized recently through Client and not verified by server yet
            if(!$PaddleTransaction->transaction) return;
            else if($PaddleTransaction->transaction && $PaddleTransaction->subscription_token)
                $PaddleTransaction->createSubscriptionByTransaction('webhook.subscription.verified');

            // Process Webhook
            $PaddleTransaction->completeTransaction('webhook.transaction.completed');

            // Add Access
            AccessHandler::addUserAccess(
                $PaddleTransaction->transaction->user_id,
                $PaddleTransaction->access_token,
                $PaddleTransaction->quantity,
                $PaddleTransaction->limitStorage,
                $PaddleTransaction->expiration_date,
                $PaddleTransaction->price_id,
                $PaddleTransaction->transaction->id,
                'webhook.access.granted'
            );

            // Close transaction, after access granted
            $PaddleTransaction->closeTransaction();
        } catch (Exception $e) {
            Log::channel('webhooks')->error("InitiateUserAccess error: " . now(), ['error' => $e->getMessage()]);
            $PaddleTransaction->transaction?->update([
                'status' => 'failed',
                'message' => 'webhook.access.error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove user access
     *
     * @param array $contentData
     * @return void
     */
    private function cancelUserAccess(array $contentData)
    {
        try {
            $PaddleTransaction = new PaddleTransactionHandler(
                PaddleTransactions::where('transaction_token', $contentData['id'])->first()
            );
    
            if($transaction = $PaddleTransaction?->transaction) {
                AccessHandler::cancelTransactionAccess(
                    $transaction,
                    'canceled.by.webhook'
                );
            }
        } catch (Exception $e) {
            Log::channel('webhooks')->error("CancelUserAccess error: " . now(), ['error' => $e->getMessage()]);
        }
    }
}
