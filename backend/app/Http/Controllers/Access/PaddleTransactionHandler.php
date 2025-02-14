<?php

namespace App\Http\Controllers\Access;

use Carbon\Carbon;
use App\Models\PaddlePrices;
use App\Models\PaddleTransactions;
use App\Http\Controllers\Access\AccessHandler;
use App\Models\PaddleSubscriptions;

class PaddleTransactionHandler
{
    // Access
    public $user_id = null;
    public $access = null;
    
    // Transaction
    public $transaction = null;
    public $transaction_token = null;

    // Subscription
    public $subscription = null;
    public $subscription_token = null;
    
    // Price
    public $price = null;
    public $price_token = null;
    public $price_id = null;
    
    // Access
    public $access_token = 'default-token';
    public $access_quantity = 1;
    public $expiration_date = null;
    public $quantity = 1;
    
    // Meta
    public $status = 'processing...';
    public $customer_token = null;
    public $total = 0.00;
    public $tax = 0.00;
    public $currencyCode = 'CHF';

    /**
     * Set default attributes
     * Webhook: "\Listeners\PaddleWebhookListener"
     *
     * @param object|null $transactionEntry
     */
    function __construct(object $transactionEntry = null)
    {
        if(!$transactionEntry) return;
        $this->transaction = $transactionEntry;
        $this->transaction_token = $this->transaction->transaction_token;
        $this->user_id = $this->transaction->user_id;
    }

    /**
     * Sets attributes, to verify transaction and define user access
     * Transaction webhook doc: https://developer.paddle.com/webhooks/overview
     * 
     * Logic: User access
     * User gains access, according transaction data
     *  - Important: The transaction-price-object defines the user access
     *      - ref. "\Access\PaddlePriceHandler"
     * 
     * Logic: User access expiration
     * Access expiration will be defined either by quantity or expiration_date
     *  - ref. "\Controllers\Access\AccessHandler"
     *
     * @param array $contentData
     * @return void
     */
    public function setTransactionAccessAttributes(array $contentData): void
    {
        // Transaction Attributes
        $item = $contentData['items'][0] ?? null;
        $this->transaction_token = $contentData['id'];
        $this->price_token = $item['price']['id'] ?? null;
        $this->customer_token = $contentData['customer_id'];
        $this->total = ((float) $contentData['details']['totals']['total']) / 100 ?? 0;
        $this->tax = ((float) $contentData['details']['totals']['tax']) / 100 ?? 0;
        $this->currencyCode = $contentData['currency_code'] ?? 'CHF';
        $this->status = $contentData['status'];

        // Price
        $this->price = PaddlePrices::where('price_token', $this->price_token)->first();
        $this->price_id = $this->price?->id;
        
        // Access token
        $this->access_token = $this->price->access_token 
            ?? $item['price']['custom_data']['access_token']
                ?? $this->access_token;
        
        // Access quantity
        $this->quantity = (int) $item['quantity'] ?? 0;

        // Access expiration date
        $defaultPeriod = $item['price']['custom_data']['duration_months'] ?? 0;
        $this->expiration_date = $contentData['current_billing_period']['ends_at'] 
            ?? $contentData['billing_period']['ends_at']
                ?? $this->calculateLatestUserExpirationDate($defaultPeriod);

        // Subscription
        $this->subscription_token = $contentData['subscription_id'] ?? null;
    }

    /**
     * Intitalize user transaction
     *
     * @param integer $userID
     * @param string $transactionToken
     * @param string $message
     * @return void
     */
    public function initializeUserTransaction(int $userID, string $transactionToken, string $message = 'transaction.initialized'): void
    {
        $this->transaction = PaddleTransactions::firstOrCreate([
            'user_id' => $userID,
            'transaction_token' => $transactionToken
        ], [
            'status' => 'initialized',
            'message' => $message,
        ]);
    }

    /**
     * Set subscription if initial transaction is type "subscription"
     *
     * @param [type] $message
     * @return void
     */
    public function createSubscriptionByTransaction($message = 'subscription.initialized'): void
    {
        if(!$this->subscription_token || !$this->user_id) return;
        $this->subscription = PaddleSubscriptions::firstOrCreate([
            'user_id' => $this->user_id,
            'subscription_token' => $this->subscription_token,
        ], [
            'price_id' => $this->price_id,
            'started_at' => now(),
            'status' => 'active',
            'message' => $message
        ]);
    }

    /**
     * Validate user by provided subscription token
     * Note: This occurs only if price is type "subscription"
     *
     * @param string $subscriptionToken
     * @param string $message
     * @return void
     */
    public function initializeUserTransactionBySubscription(string $subscriptionToken, $message): void
    {
        $this->subscription = PaddleSubscriptions::where('subscription_token', $subscriptionToken)->first();
        if(!$this->subscription) return;
        $this->user_id = $this->subscription->user_id;
        $this->initializeUserTransaction(
            $this->user_id, 
            $this->transaction_token, 
            $message
        );
    }

    /**
     * Complete transaction
     * Note: Before user access granted
     *
     * @param string $message
     * @return void
     */
    public function completeTransaction(string $message): void
    {
        if(!$this->transaction) return;
        $this->transaction->update([
            'subscription_id' => $this->subscription?->id,
            'customer_token' => $this->customer_token,
            'price_id' => $this->price_id,
            'quantity' => $this->quantity,
            'total' => $this->total,
            'tax' => $this->tax,
            'currency_code' => $this->currencyCode,
            'is_verified' => true,
            'status' => $this->status,
            'message' => $message
        ]);
    }

    /**
     * Close transaction 
     * Note: After user access granted
     *
     * @return void
     */
    public function closeTransaction(): void
    {
        $this->transaction->update([
            'access_added' => true,
            'is_verified' => true,
            'status' => 'completed',
            'message' => 'user.access.granted'
        ]);
    }

    /**
     * Calculate user access expiration date
     * Note: This scenario only applies to one-time purchases
     *  > Make sure 'custom_data' contains a "duration_period" 
     *
     * @param integer $accessPeriod
     * @return string
     */
    private function calculateLatestUserExpirationDate(int $accessPeriod): string
    {
        $expirationDate = Carbon::now()->addMonths($accessPeriod * $this->quantity);
        $currentAccess = AccessHandler::checkUserAccessByToken($this->transaction->user_id, $this->access_token);

        // Current access is active
        // Add month to current expiration Date
        if($currentAccess) {
            $expirationDate = Carbon::parse($currentAccess->expiration_date);
            $expirationDate = $expirationDate->addMonths($accessPeriod * $this->quantity);
        }

        return $expirationDate;
    }
}
