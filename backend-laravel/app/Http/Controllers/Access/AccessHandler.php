<?php

namespace App\Http\Controllers\Access;

use App\Models\PaddleTransactions;
use App\Models\UserAccess;

class AccessHandler
{
    /**
     * System Relevant Admin Access
     *  > Must be set via Admin Panel
     *
     * @var string
     */
    public static $accessAdminToken = 'access-admin';       // Private

    /**
     * User Access Management
     * Defines user access within our app
     * 
     * Public Access Tokens: 
     * Allow users gain access via price purchase 
     *  - ref. "\Access\PaddlePriceHandler"
     *
     * @var string
     */
    public static $accessCockpitToken = 'access-cockpit';   // Via Transactions
    
    /**
     * Quantity Limit of something
     *
     * @var integer
     */
    public static $accessCockpitFreeQuantity = 12;

    /**
     * Storage Size Limit [GB]
     *  Free Access
     *
     * @var integer
     */
    public static $accessCockpitFreeStorage = 1;

    /**
     * Maximum Amount of Google Cloud Platform Storage
     *
     * @var integer
     */
    public static $accessCockpitStorageFilesLimit = 199;
    
    /**
     * Add user app access
     * 
     * Note: 
     * For peroformance purpose, we update current access.
     *
     * @param integer $userID
     * @param string $accessToken
     * @param integer|null $quantity
     * @param integer|null $storageLimit
     * @param string|null $expirationDate
     * @param integer|null $priceID
     * @param integer|null $transactionID
     * @param string|null $message
     * @return UserAccess
     */
    static public function addUserAccess(int $userID, string $accessToken = 'access-undefined', ?int $quantity = 1, ?int $storageLimit = 1, ?string $expirationDate,  ?int $priceID = null, ?int $transactionID = null, ?string $message = null): UserAccess
    {
        $userAccess = UserAccess::firstOrNew([
            'user_id' => $userID,
            'access_token' => $accessToken,
        ]);

        // Access Details
        $userAccess->quantity = $quantity;
        $userAccess->limit_storage = $storageLimit;
        $userAccess->expiration_date = $expirationDate;

        // Update other fields
        $userAccess->price_id = $priceID;
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
    static public function getUserAccessByToken(int $userID, string $accessToken): ?UserAccess
    {
        return UserAccess::where([
            'user_id' => $userID,
            'access_token' => $accessToken,
            'is_active' => true
        ])->first();
    }

    /**
     * Undocumented function
     *
     * @param integer $userID
     * @param string $accessToken
     * @return UserAccess|null
     */
    static public function resetUserAccess(int $userID, string $accessToken): ?UserAccess
    {
        if( $userAccess = SELF::getUserAccessByToken($userID, $accessToken)) {
            $userAccess->price_id = null;
            $userAccess->expiration_date = null;
            $userAccess->quantity = SELF::$accessCockpitFreeQuantity;
            $userAccess->limit_storage = SELF::$accessCockpitFreeStorage;
            $userAccess->save();
            return $userAccess;
        }

        return null;
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
            ->orderBy('access_token')   // Ensure the distinct on access_token works
            ->orderByDesc('updated_at')
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
        // Retrieve the latest UserAccess entry
        $price = $transaction->belongs_to_price;
        $userAccess = UserAccess::where([
            'user_id' => $transaction->user_id,
            'access_token' => $price->access_token,
        ])->first();

        if ($userAccess) {

            // Latest expiration date
            $latestExpirationDate = null;
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
            
            $userAccess->update([
                'expiration_date' => $latestExpirationDate,
                'quantity' => SELF::$accessCockpitFreeQuantity,
                'limit_storage' => SELF::$accessCockpitFreeStorage
            ]);
        }

        $transaction->update([
            'canceled_at' => now(),
            'status' => 'canceled',
            'message' => $message,
        ]);
    }
}
