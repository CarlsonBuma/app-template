<?php

namespace Database\Seeders;

use Exception;
use App\Models\Cockpit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Access\AccessHandler;

class UserSeeder extends Seeder
{
    public function run()
    {
        try {
            $userTable = DB::table('users');
            DB::beginTransaction();

                // Create user
                $userID = $userTable->insertGetId([
                    'name' => 'Owner',
                    'email' =>'admin@admin.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make('admin'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // User entity
                Cockpit::create([
                    'user_id' => $userID,
                ]);

                AccessHandler::addUserAccess(
                    $userID,
                    AccessHandler::$accessAdminToken,
                    1000,                                       // Event Limit
                    100,
                    '2049-12-31',                               // Expiration Date
                    null,
                    null,
                    'created.by.seeder'
                );

                // Dummy users
                for($x = 0; $x < 20; $x++) {
                    
                    // Dummy user
                    $id = $userTable->insertGetId([
                        'name' => 'User' . $x,
                        'email' =>'user' . $x .'@user.com',
                        'email_verified_at' => now(),
                        'password' => Hash::make('test'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // User entity
                    Cockpit::create([
                        'user_id' => $id,
                    ]);
                }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }
}
