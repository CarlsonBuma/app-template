<?php

namespace App\Http\Collections;

use App\Models\PaddleSubscriptions;
use App\Http\Controllers\Access\AccessHandler;
use App\Models\PaddlePrices;
use App\Models\UserAccess;

abstract class AccessCollection
{   
    /**
     * Undocumented function
     *
     * @param UserAccess|null $access
     * @return array|null
     */
    static public function render_user_access(?UserAccess $access): ?array
    {
        if(!$access) return null;
        return [
            '_type' => 'Collection $access',
            'id' => $access->id,
            'is_active' => $access->is_active,
            'access_token' => $access->access_token,
            'quantity' => $access->quantity,
            'limit_storage' => (float) $access->limit_storage,
            'expiration_date' => $access->expiration_date,
            'price' => SELF::render_app_price($access?->belongs_to_price),
            'subscription' => SELF::render_user_subscription($access->belongs_to_transaction?->belongs_to_subscription),
            'status' => $access->status,
            'message' => $access->message,
        ];
    }

    /**
     * Undocumented function
     *
     * @param PaddlePrices|null $price
     * @return array|null
     */
    static public function render_app_price(?PaddlePrices $price): ?array
    {
        if(!$price) return null;
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
            'access_token' => $price->access_token,
            'limit_quantity' => $price->limit_quantity,
            'limit_storage' => $price->limit_storage,
            'duration_months' => $price->duration_months,
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
    static public function render_user_price_access(object $price, int $userID): array
    {

        $userAccess = $price->has_access()->where([
            'user_id' => $userID,
            'is_active' => true
        ])->first();

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
            'access_token' => $price->access_token,
            'limit_quantity' => $price->limit_quantity,
            'limit_storage' => (float) $price->limit_storage,
            'duration_months' => $price->duration_months,
            'has_access' => SELF::render_user_access($userAccess),
            'has_active_subscription' => PaddleSubscriptions::where([
                    'user_id' => $userID,
                    'price_id' => $price->id,
                    'canceled_at' => null,
                ])->exists(),
        ];
    }

    /**
     * Get price
     *  > Check for active user subscriptions in current price
     *
     * @param object $price
     * @return array
     */
    static public function render_user_transaction(object $transaction): array
    {
        $price = $transaction->belongs_to_price;
        return [
            '_type' => 'Collection $transaction',
            'id' => $transaction->id,
            'transaction_token' => $transaction->transaction_token,
            'price_id' => $transaction->price_id,
            'total' => $transaction->total,
            'tax' => $transaction->tax,
            'currency_code' => $transaction->currency_code,
            'quantity' => $transaction->quantity,
            'expiration_date' => $transaction->expiration_date,
            'canceled_at' => $transaction->canceled_at,
            'status' => $transaction->status,
            'message' => $transaction->message,
            'created_at' => $transaction->created_at,
            'updated_at' => $transaction->updated_at,
            'price' => $price ? SELF::render_user_price_access($price, $transaction->user_id) : null,
            'access' => $price ? AccessHandler::getUserAccessByToken($transaction->user_id, $price->access_token) : null
        ];
    }

    /**
     * Undocumented function
     *
     * @param object|null $subscription
     * @return array|null
     */
    static public function render_user_subscription(?object $subscription): ?array
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
}