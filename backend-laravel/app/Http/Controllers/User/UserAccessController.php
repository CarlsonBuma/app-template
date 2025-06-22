<?php

namespace App\Http\Controllers\User;

use Exception;
use App\Models\UserAccess;
use App\Models\PaddlePrices;
use Illuminate\Http\Request;
use App\Models\CockpitStorage;
use App\Providers\PaddleProvider;
use App\Models\PaddleTransactions;
use App\Models\PaddleSubscriptions;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Exception\GuzzleException;
use App\Http\Collections\AccessCollection;
use App\Http\Controllers\Access\AccessHandler;
use App\Http\Controllers\Access\PaddleTransactionHandler;

class UserAccessController extends Controller
{
    /**
     * Check user access by "access_token"
     *
     * @param string $access_token
     * @return void
     */
    public function checkUserAccess(string $access_token)
    {
        $userAccess = AccessHandler::getUserAccessByToken(Auth::id(), $access_token);
        return response()->json([
            'access' => $userAccess,
            'access_token' => $access_token,
            'message' => 'Latest access token.',
        ], 200);
    }

    /**
     * Load prices, transactions and access
     *
     * @return void
     */
    public function loadUserTransactions()
    {
        $userTransactions = PaddleTransactions::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($transaction) {
                return AccessCollection::render_user_transaction($transaction);
            });

        return response()->json([
            'transactions' => $userTransactions,
            'message' => 'Transactions loaded.',
        ], 200);
    }
    
    /**
     * Loads User Pricing Plan against token
     * and Checks if User already has a subscription
     *
     * @param string $access_token
     * @return void
     */
    public function loadUserPricingPlans(Request $request)
    {
        $userID = Auth::id();
        $data = $request->validate([
            'access_token' => ['required', 'string'],
        ]);

        // Check if subsciption exists already
        $hasCommunitySubscription = UserAccess::where([
                'user_id' => $userID,
                'access_token' => AccessHandler::$accessCockpitToken
            ])->whereNotNull('price_id')
            ->exists();

        // UserPrice
        $prices = PaddlePrices::where([
                'access_token' => $data['access_token'],
                'is_active' => true,
                'status' => 'active'
            ])->orderBy('price', 'asc')
            ->get()
            ->map(function ($price) use($userID) {
                return AccessCollection::render_user_price_access($price, $userID);
            });

        // Metrics
        $cockpit = Auth::user()->has_cockpit;
        $userAccess =  AccessHandler::getUserAccessByToken($userID, AccessHandler::$accessCockpitToken);
        $storageSize = CockpitStorage::where('cockpit_id', $cockpit?->id)->sum('size_bytes');
        $storageSizeGB = number_format($storageSize / (1024 ** 3), 2);

        return response()->json([
            'prices' => $prices,
            'has_community_subscription' =>  $hasCommunitySubscription,
            'community_access' => AccessCollection::render_user_access($userAccess),
            'storage_usage' => $storageSizeGB
        ], 200);
    }
    
    /**
     * Initialize user's client checkout.
     * 
     * Logic:
     * This marks the starting point of the entire user access verification process
     *  > A "transaction_token" is assigned to the user for subsequent webhook verifications
     *  > Further verification will be handled by "/Listeners/PaddleWebhookListener"
     * 
     * @param Request $request
     * @return void
     */
    public function initializeClientCheckout(Request $request) 
    {
        $data = $request->validate([
            'transaction_token' => ['required', 'string'],
            'customer_token' => ['required', 'string'],
        ]);

        $PaddleTransaction = new PaddleTransactionHandler(null);
        $PaddleTransaction->initializeUserTransaction(
            Auth::id(), 
            $data['transaction_token'],
            'client.checkout.initialized'
        );

        return response()->json([
            'transaction' => $PaddleTransaction->transaction,
            'message' => 'Checkout initialized.',
        ], 200);
    }

    /**
     * Verify user transaction by "transaction_token"
     * Check if transaction has been already verified by webhook successfully
     *
     * @param Request $request
     * @return void
     */
    public function verifyUserTransaction(Request $request)
    {
        $data = $request->validate([
            'transaction_token' => ['required', 'string'],
        ]);

        $userTransaction = PaddleTransactions::where([
            'user_id' => Auth::id(),
            'transaction_token' => $data['transaction_token']
        ])->first();

        // Verify Request
        if(!$userTransaction) {
            return response()->json([
                'message' => 'Invalid request.',
            ], 422);
        }
        
        // Check if transaction has been verified by webhook
        $userAccess = UserAccess::where([
            'user_id' => Auth::id(),
            'transaction_id' => $userTransaction->id,   // Unique
            'is_active' => true,
        ])->first();

        if($userAccess) {
            return response()->json([
                'access' => AccessCollection::render_user_access($userAccess),
                'price_id' => $userTransaction->price_id,
                'message' => 'Access granted.',
            ], 200);
        }

        return response()->json([
            'message' => 'Access verification may takes a few seconds.',
        ], 200);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function createFreeCommunityAccess()
    {
        // Check already issued
        if($userAccess = AccessHandler::getUserAccessByToken(Auth::id(), AccessHandler::$accessCockpitToken)) {
            return response()->json([
                'message' => 'Access already issued.',
            ], 422);
        }

        // Create free Access
        $userAccess = AccessHandler::addUserAccess(
            Auth::id(),
            AccessHandler::$accessCockpitToken,
            AccessHandler::$accessCockpitFreeQuantity,            // Event Limit
            AccessHandler::$accessCockpitFreeStorage,      // Storage Limit
            null,
            null,
            null,
            'created.by.free.access'
        );

        return response()->json([
            'access' => AccessCollection::render_user_access($userAccess),
            'message' => 'Free access has been granted.',
        ], 200);
    }

    /**
     * Cancel user price subscription within Paddle
     *  > Request via paddle api-call
     *  > https://developer.paddle.com/api-reference/subscriptions/cancel-subscription
     * 
     * Note: 
     * Allows user to cancel Paddle price subscription at any time.
     * 
     * @param Request $request
     * @return void
     */
    public function cancelSubscription(Request $request)
    {
        $userCommunityAccess = null;
        $userSubscription = null;
        $data = $request->validate([
            'price_token' => ['required', 'string'],
        ]);

        try {
            // Verify Price
            $price = PaddlePrices::where('price_token', $data['price_token'])->first();
            if(!$price) throw new Exception('Invalid request.');
            
            // Process all active subscriptions
            $subscriptions = PaddleSubscriptions::where([
                'price_id' => $price->id,
                'user_id' => Auth::id(),
                'canceled_at' => null,
            ])->get();

            // Handle cancleation of subscription(s)
            $PaddleProvider = new PaddleProvider();
            foreach($subscriptions as $subscription) {
                $userSubscription = $subscription;
                if($PaddleProvider->cancelSubscription($subscription->subscription_token)) {
                    $subscription->update([
                        'canceled_at' => now(),
                        'status' => 'canceled',
                        'message' => 'client.subscription.canceled'
                    ]);
                }
            }

            // Reset Access
            if($price->access_token === AccessHandler::$accessCockpitToken)
                $userCommunityAccess = AccessHandler::resetUserAccess(Auth::id(), AccessHandler::$accessCockpitToken);
        } 

        // Error by Request
        catch (GuzzleException $e) {
            $userSubscription?->update([
                'message' => 'request.subscription.cancel.error: ' . $e->getMessage(),
            ]);

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        } 

        // Process error        
        catch (Exception $e) {
            $userSubscription?->update([
                'message' => 'server.subscription.cancel.error' . $e->getMessage()
            ]);

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'community_access' => AccessCollection::render_user_access($userCommunityAccess), 
            'message' => 'Subscription canceled.',
        ], 200);
    }
}
