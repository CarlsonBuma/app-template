<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PaddleSubscriptions;
use Illuminate\Support\Facades\Auth;


class PaddleNoActiveSubscriptions
{
    /**
     * Restrict actions to ensure no active subscriptions.
     * 
     * Logic:
     * Subscriptions are periodically billed via Paddle.
     * It is critical to ensure that current users are not adversely affected by app actions.
     * 
     * Example: 
     * If a user deletes their account while having active subscriptions within our external provider,
     * this could cause billing issues or service disruptions.
     *
     * @param Request $request
     * @param Closure $next
     * @return void
     */
    public function handle(Request $request, Closure $next)
    {  
        if($user = Auth::guard('api')->user()) {
            if(!PaddleSubscriptions::where([
                    'user_id' => $user->id,
                    'canceled_at' => null,
                ])->exists()
            ) return $next($request);
        }
         

        return response()->json([
            'status' => 'active_subscriptions',
            'message' => 'Please cancel active subscriptions.',
        ], 422);
    }
}
