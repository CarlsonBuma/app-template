<?php

namespace App\Http\Collections;

use Carbon\Carbon;
use App\Models\PaddleSubscriptions;
use App\Http\Controllers\Access\AccessHandler;

abstract class AccessCollection
{   
    /**
     * User access
     *
     * @param object $access
     * @return array
     */
    static public function renderUserAccess(object $access): array
    {
        return [
            '_type' => 'Collection $access',
            'id' => $access->id,
            'is_active' => $access->is_active,
            'access_token' => $access->access_token,
            'quantity' => $access->quantity,
            'expiration_date' => Carbon::parse($access->expiration_date)->format('Y-m-d'),
            'price' => $access->belongs_to_transaction?->belongs_to_price,
            'access' => AccessHandler::checkAccessByID($access->id),
            'subscription' => SELF::renderUserSubscription($access->belongs_to_transaction?->belongs_to_subscription),
            'status' => $access->status,
            'message' => $access->message,
        ];
    }

    /**
     * Undocumented function
     *
     * @param object|null $subscription
     * @return array|null
     */
    static public function renderUserSubscription(object $subscription = null): ?array
    {
        if(!$subscription) return null;
        return [
            '_type' => 'Collection $subscription',
            'id' => $subscription->id,
            'canceled_at' => $subscription->canceled_at,
            'paused_at' => $subscription->paused_at,
            'started_at' => $subscription->started_at,
            'status' => $subscription->status,
            'message' => $subscription->message,
        ];
    }

    /**
     * Get price
     *  > Check for active user subscriptions in current price
     *
     * @param object $price
     * @param integer $userID
     * @return array
     */
    static public function renderUserPrice(object $price, int $userID): array
    {
        return [
            '_type' => 'Collection $price',
            'id' => $price->id,
            'price_token' => $price->price_token,
            'name' => $price->name,
            'type' => $price->type,
            'price' => $price->price,
            'currency_code' => $price->currency_code,
            'billing_interval' => $price->billing_interval,
            'billing_frequency' => $price->billing_frequency,
            'trial_interval' => $price->trial_interval,
            'trial_frequency' => $price->trial_frequency,
            'tax_mode' => $price->tax_mode,
            'duration_months' => $price->duration_months,
            'access_token' => $price->access_token,
            'has_access' => AccessHandler::checkUserAccessByToken($userID, $price->access_token),
            'status' => $price->status,
            'message' => $price->message,
            'is_subscription' => $price->trial_interval && $price->trial_frequency,
            'has_active_subscription' => PaddleSubscriptions::where([
                    'user_id' => $userID,
                    'price_id' => $price->id,
                    'canceled_at' => null,
                ])->exists()
        ];
    }

    /**
     * Get price
     *  > Check for active user subscriptions in current price
     *
     * @param object $price
     * @return array
     */
    static public function renderUserTransaction(object $transaction): array
    {
        $price = $transaction->belongs_to_price;

        return [
            '_type' => 'Collection $transaction',
            'id' => $transaction->id,
            'transaction_token' => $transaction->transaction_token,
            'price_id' => $transaction->price_id,
            'quantity' => $transaction->quantity,
            'total' => $transaction->total,
            'tax' => $transaction->tax,
            'currency_code' => $transaction->currency_code,
            'status' => $transaction->status,
            'message' => $transaction->message,
            'created_at' => Carbon::parse($transaction->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($transaction->updated_at)->format('Y-m-d H:i:s'),
            'price' => $price ? SELF::renderUserPrice($price, $transaction->user_id) : null,
            'access' => $price ? AccessHandler::checkUserAccessByToken($transaction->user_id, $price->access_token) : null
        ];
    }
}