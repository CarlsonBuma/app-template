<?php

namespace App\Http\Controllers\Access;

use App\Models\PaddleSubscriptions;

class PaddleSubscriptionHandler
{
    public $subscription = null;

    /**
     * Update current Subscription
     * Webhook: "\Listeners\PaddleWebhookListener"
     *
     * @param array $contentData
     * @return void
     */
    public function updateSubscriptionByWebhook(array $contentData): void
    {
        $this->subscription = PaddleSubscriptions::where([
            'subscription_token' => $contentData['id'],         // Required by access logic
        ])->first();
        
        $this->subscription?->update([
            'started_at' => $contentData['started_at'],         // Optional
            'canceled_at' => $contentData['canceled_at'],       // Optional
            'paused_at' => $contentData['paused_at'],           // Optional
            'status' => $contentData['status'],                 // Optional
            'message' => 'webhook.subscription.updated'         // Optional
        ]);
    }
}
