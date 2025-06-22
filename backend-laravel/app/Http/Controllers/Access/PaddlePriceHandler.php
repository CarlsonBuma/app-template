<?php

namespace App\Http\Controllers\Access;

use App\Models\PaddlePrices;
use App\Http\Controllers\Access\AccessHandler;

class PaddlePriceHandler
{
    public $price = null;

    /**
     * Update price changes that have been triggered within Paddle
     *  - Sandbox: https://sandbox-vendors.paddle.com/products-v2
     *  - Webhook: "\Listeners\PaddleWebhookListener"
     * 
     * Setup:
     *  1. Implement price-access-token within "updatePriceByWebhook()"
     *      - Define new access-tokens in "\Access\AccessHandler"
     *      - May add logic to handle new access-token accordingly within app
     *  2. Set Price and its attributes within Paddle
     *      - Ensure 'custom_data' is included in the price configuration
     *          > 'access_token' (string, required): Defines access to the app and its features
     *          > 'limit_quantity: (int, required)
     *          > 'limit_storage: (int, required)
     *          > 'duration_months' (int, optional): Defines the period of access
     *              > Userful in case of One-Time-Purchase Expiration Date
     *              > Note: This is overridden by the subscription.billing_period.ends_at value
     *
     * @param array $contentData
     * @return void 
     */
    public function updatePriceByWebhook(array $contentData): void
    {
        $accessTokenByPaddle = $contentData['custom_data']['access_token'] ?? null;
        if($accessTokenByPaddle && (
            
            // Price is available and defined within app
            // Add other prices tokens here...
            $accessTokenByPaddle === AccessHandler::$accessCockpitToken
            
        )) { 
            
            $this->price = PaddlePrices::updateOrCreate([
                'price_token' => $contentData['id'],                                                // Required by access logic
            ], [
                'product_token' => $contentData['product_id'],                                      // Optional
                'name' => $contentData['name'],                                                     // Optional
                'description' => $contentData['description'],                                       // Optional
                'type' => $contentData['billing_cycle'] ? 'subscription' : 'one-time-purchase',     // Optional
                'price' => $contentData['unit_price']['amount'] / 100 ?? null,                      // Optional
                'tax_mode' => $contentData['tax_mode'] === 'external' ? 'excluded' : 'included',    // Optional
                'currency_code' => $contentData['unit_price']['currency_code'] ?? null,             // Optional
                'billing_interval' => $contentData['billing_cycle']['interval'] ?? null,            // Optional
                'billing_frequency' => $contentData['billing_cycle']['frequency'] ?? null,          // Optional
                'trial_interval' => $contentData['trial_period']['interval'] ?? null,               // Optional
                'trial_frequency' => $contentData['trial_period']['frequency'] ?? null,             // Optional
                'access_token' => $contentData['custom_data']['access_token'] ?? 'undefined',       // Required by access logic
                'limit_quantity' => $contentData['custom_data']['limit_quantity'] ?? 0,                 // Optional by access logic
                'limit_storage' => $contentData['custom_data']['limit_storage'] ?? 0,          // Optional by access logic
                'duration_months' => $contentData['custom_data']['duration_months'] ?? 0,           // Optional by access logic
                'status' => $contentData['status'],                                                 // Optional
                'message' => 'webhook.price.updated',                                               // Optional
            ]);
        }
    }
}
