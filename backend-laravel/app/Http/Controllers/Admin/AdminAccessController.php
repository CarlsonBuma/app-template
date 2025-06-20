<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\UserAccess;
use App\Models\PaddlePrices;
use Illuminate\Http\Request;
use App\Models\PaddleTransactions;
use App\Http\Controllers\Controller;
use App\Http\Collections\AccessCollection;
use App\Http\Controllers\Access\AccessHandler;

class AdminAccessController extends Controller
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function loadPrices()
    {
        $prices = PaddlePrices::orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'prices' => $prices,
            'message' => 'Prices loaded.',
        ], 200);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function updatePrice(Request $request)
    {
        $data = $request->validate([
            'price_id' => ['required', 'numeric'],
            'is_active' => ['required', 'boolean'],
        ]);

        PaddlePrices::find($data['price_id'])?->update([
            'is_active' => (bool) $data['is_active']
        ]);

        return response()->json([
            'message' => 'Price updated.',
        ], 200);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function loadUserAccess(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string'],
        ]);

        if($user = User::where('email', $data['email'])->first()) {

            $userAccess = AccessHandler::getLatestUserAccesses($user->id)
                ->map(function($access) {
                    return AccessCollection::render_user_access($access);
                });

            $userTransactions = PaddleTransactions::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($transaction) {
                    return AccessCollection::render_user_transaction($transaction);
                });
            
            return response()->json([
                'latest_access' => $userAccess,
                'transactions' => $userTransactions,
                'message' => 'User access loaded.',
            ], 200);
        }

        return response()->json([
            'message' => 'Email does not exist.',
        ], 422);
    }


    /**
     * Add user access
     * 
     * Restrictions:
     *  > Access upgrade to active subscriptions must be denied.
     *
     * @param Request $request
     * @return void
     */
    public function createUserAccess(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string'],
            'access_token' => ['required', 'string'],
            'quantity' => ['nullable', 'numeric'],
            'expiration_date' => ['nullable', 'string'],
        ]);

        if($user = User::where('email', $data['email'])->first()) {

            // Restrictions
            if($this->hasActiveSubscription($user->id, $data['access_token'])) {
                return response()->json([
                    'message' => 'User has active subscriptions.',
                ], 422);
            }

            // Add Access
            UserAccess::firstOrNew([
                'user_id' => $user->id,
                'access_token' => $data['access_token'],
            ]);
            
            $access = AccessHandler::addUserAccess(
                $user->id,
                $data['access_token'],
                $data['quantity'] ?? 0,                         // Event Limit
                AccessHandler::$accessCockpitFreeStorage,      // Storage Limit
                isset($data['expiration_date']) ? $data['expiration_date'] : null,
                null,
                null,
                'created.by.admin'
            );
            
            return response()->json([
                'access' => AccessCollection::render_user_access($access),
                'message' => 'Access granted.',
            ], 200);
        }

        return response()->json([
            'message' => 'No user found.',
        ], 422);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function updateUserAccess(Request $request)
    {
        $data = $request->validate([
            'access_id' => ['required', 'numeric'],
            'is_active' => ['required', 'boolean'],
        ]);

        UserAccess::find($data['access_id'])?->update([
            'is_active' => $data['is_active']
        ]);

        return response()->json([
            'message' => 'Access updated.',
        ], 200);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function cancelTransaction(Request $request)
    {
        $data = $request->validate([
            'transaction_id' => ['required', 'numeric'],
        ]);

        if($transaction = PaddleTransactions::where('id', $data['transaction_id'])->whereNull('canceled_at')->first()) {
            AccessHandler::cancelTransactionAccess($transaction, 'canceled.by.admin');
            return response()->json([
                'canceled_at' => now(),
                'message' => 'Transaction canceled.',
            ], 200);
        }

        return response()->json([
            'message' => 'Transaction not found or already canceled.',
        ], 422);
    }

    /**
     * Check if a user has an active subscription.
     *
     * @param int $userID
     * @param string $token
     * @return bool
     */
    private function hasActiveSubscription(int $userID, string $token): bool
    {
        // Retrieve UserAccess records that match the user ID and access token.
        $accessRecords = UserAccess::where([
            'user_id' => $userID,
            'access_token' => $token,
        ])->get();

        // Check if any of the user's subscriptions associated with these records are active.
        foreach ($accessRecords as $access) {
            $userSubscriptionToAccess = $access->belongs_to_transaction?->belongs_to_subscription;
            if ($userSubscriptionToAccess && $userSubscriptionToAccess->status === 'active') {
                return true; // Active subscription found
            }
        }

        return false; // No active subscriptions found
    }
}
