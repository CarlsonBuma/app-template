<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;


class PaddleWebhookVerification
{
    /**
     * Verify paddle webhooks
     *  - Doc: https://developer.paddle.com/webhooks/signature-verification#extract-ts-h1
     *  > Note: Validate against PADDLE_WEBHOOK_SECRET
     *
     * @param Request $request
     * @param Closure $next
     * @return void
     */
    public function handle(Request $request, Closure $next)
    {  
        try {
            // signature format "ts=...;h1=..."
            $payload = $request->getContent();
            $signature = $request->header('Paddle-Signature');

            // Extract the timestamp and signature (h1)
            $pairs = explode(';', $signature);
            $data = [];

            foreach ($pairs as $pair) {
                [$key, $value] = explode('=', $pair, 2);
                $data[$key] = $value;
            }

            $timestamp = $data['ts'] ?? null;
            $receivedSignature = $data['h1'] ?? null;
            if (!$timestamp || !$receivedSignature) {
                return response()->json([
                    'message' => 'Invalid payload'
                ], 400);
            }

            // Verify timestamp to prevent replay attacks
            // 5 minutes tolerance
            if (abs(time() - $timestamp) > 300) { 
                return response()->json([
                    'message' => 'Expired timestamp'
                ], 403);
            }

            // Calculate the HMAC-SHA256 hash using your Paddle Webhook Secret
            $signedPayload = "{$timestamp}:{$payload}";
            $calculatedSignature = hash_hmac('sha256', $signedPayload, config('paddle.webhook_secret'));

            // Verified
            if(hash_equals($calculatedSignature, $receivedSignature)) {
                return $next($request);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Webhook verification error.',
            ], 500);
        }

        return response()->json([
            'message' => 'Invalid signature.'
        ], 403);
    }
}
