<?php

namespace Database\Seeders;

use Exception;
use App\Models\Cockpit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\User\Access\AccessHandler;

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

                // Grant access admin
                AccessHandler::addUserAccess(
                    $userID,
                    null,
                    AccessHandler::$tokenAdmin,
                    1000,
                    '2049-12-31',
                    'created.by.seeder'
                );

                // Grant feature access to cockpit
                AccessHandler::addUserAccess(
                    $userID,
                    null,
                    AccessHandler::$tokenCockpit,
                    1000,
                    '2049-12-31',
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
