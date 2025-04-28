<?php

namespace App\Http\Controllers\Access;

use App\Models\PaddleTransactions;
use App\Models\UserAccess;

class AccessHandler
{
    /**
     * User Access Management
     * Defines user access within our app
     * 
     * Public Access Tokens: 
     * Allow users gain access via price purchase 
     *  - ref. "\Access\PaddlePriceHandler"
     * 
     * Private Access Tokens: 
     * Can not be issued by user itself 
     *  - eg. $tokenAdmin
     */
    public static $tokenAdmin = 'access-admin';     // Private
    public static $tokenCockpit = 'access-cockpit';

    /**
     * Add user app access
     * 
     * Note: 
     * For transpirancy purpose, we always create new access,
     * instead of updating existing access.
     *
     * @param integer $userID
     * @param integer|null $transactionID
     * @param string|null $accessToken
     * @param integer|null $quantity
     * @param string|null $expirationDate
     * @param string|null $message
     * @return object
     */
    static public function addUserAccess(int $userID, ?int $transactionID, ?string $accessToken = 'undefined', ?int $quantity = 0, ?string $expirationDate, ?string $message = null): object
    {
        $userAccess = UserAccess::firstOrNew([
            'user_id' => $userID,
            'access_token' => $accessToken,
        ]);

        // Add the new quantity to the existing quantity
        $userAccess->quantity = ($userAccess->exists ? $userAccess->quantity : 0) + $quantity;

        // Set expiration date
        $userAccess->expiration_date = $expirationDate;

        // Update other fields
        $userAccess->transaction_id = $transactionID;
        $userAccess->is_active = true;
        $userAccess->status = 'access.granted';
        $userAccess->message = $message;

        // Save the record
        $userAccess->save();
        return $userAccess;
    }

    /**
     * Get latest access, by access token
     * Check by expiration_date or quantity
     *  > Reconsider quantity if no expiration_date is given
     *
     * @param integer $userID
     * @param string $accessToken
     * @return object|null
     */
    static public function getUserAccessByToken(int $userID, string $accessToken): ?object
    {
        return UserAccess::where([
                'user_id' => $userID,
                'access_token' => $accessToken,
                'is_active' => true
            ])
            ->where(function ($query) {
                $query->whereNotNull('expiration_date')
                    ->where('expiration_date', '>=', now()) // Valid subscription
                    ->orWhere(function ($query) {
                        $query->whereNull('expiration_date')
                            ->where('quantity', '>', 0); // Valid one-time purchase
                    });
            })
            ->orderByRaw("
                CASE 
                    WHEN expiration_date IS NOT NULL AND expiration_date >= CURRENT_DATE THEN 1 
                    ELSE 2 
                END ASC
            ") // Give priority to valid subscriptions
            ->orderByDesc('expiration_date') // Order by latest expiration date for subscriptions
            ->orderByDesc('updated_at') // Order by last update for one-time purchases
            ->first();
    }


    /**
     * Get all active accesses by latest expiration_date
     * Check by expiration_date or quantity
     *  > Reconsider quantity if no expiration_date is given
     *  > Unified by access_token
     *
     * @param integer $userID
     * @return object
     */
    static public function getLatestUserAccesses(int $userID): object
    {
        return UserAccess::select('user_access.*')
            ->where([
                'user_id' => $userID,
                'is_active' => true,
            ])
            ->where(function ($query) {
                
                // For active subscriptions with expiration_date
                $query->whereNotNull('expiration_date')
                    ->whereDate('expiration_date', '>=', now())

                    // For one-time purchases where expiration_date is NULL and quantity > 0
                    ->orWhere(function ($query) {
                        $query->whereNull('expiration_date')
                            ->where('quantity', '>', 0);
                    });
            })
            ->orderBy('access_token')   // Ensure the distinct on access_token works
            ->orderByRaw("
                CASE 
                    WHEN expiration_date IS NOT NULL AND expiration_date >= CURRENT_DATE THEN 1 
                    ELSE 2 
                END DESC
            ")
            ->orderByDesc('expiration_date')
            ->orderByDesc('created_at')
            ->distinct('access_token')
            ->get();
    }

    /**
     * Remove user app access by transaction
     *
     * @param object $transaction
     * @return void
     */
    static public function cancelTransactionAccess(object $transaction, string $message): void
    {
        $id = $transaction->id;
        $price = $transaction->belongs_to_price;
        $quantity = $transaction->quantity;
        $latestExpirationDate = null;

        // Retrieve the latest UserAccess entry
        $userAccess = UserAccess::where([
            'user_id' => $transaction->user_id,
            'access_token' => $price->access_token,
        ])->latest()->first();

        if ($userAccess) {

            // Latest expiration date
            if ($transaction->expiration_date) {
                $latestExpirationTransaction = PaddleTransactions::where([
                        'user_id' => $transaction->user_id,
                        'price_id' => $price->id,
                        'is_verified' => true,
                        'access_added' => true
                    ])
                    ->where('id', '!=', $transaction->id) // Exclude the current transaction
                    ->latest('expiration_date')
                    ->first();
    
                $latestExpirationDate = $latestExpirationTransaction?->expiration_date;
            }

            // Subtract the transaction quantity from the existing quantity
            else {
                $quantity = max(0, $userAccess->quantity - $quantity);
            }
            
            $userAccess->update([
                'expiration_date' => $latestExpirationDate,
                'quantity' => $quantity
            ]);
        }

        $transaction->update([
            'canceled_at' => now(),
            'status' => 'canceled',
            'message' => $message,
        ]);
    }
}
