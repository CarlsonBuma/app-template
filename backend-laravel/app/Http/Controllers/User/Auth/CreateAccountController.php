<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use App\Models\Cockpit;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class CreateAccountController extends Controller
{
    /**
     * Registration / Create Account
     *  > Creates new User
     *  > Create user cockpit
     *
     * @param Request $request
     * @return void
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:125'],
            'email' => ['required', 'string', 'email', 'unique:users', 'max:255'],
            'terms' => ['required', 'boolean'],
            'privacy' => ['required', 'boolean'],
        ]);

        // Process
        $userID = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' =>  Hash::make(Str::random(125))
        ])->id;

        // Add dependencies
        Cockpit::create([
            'user_id' => $userID,
        ]);
        
        return response()->json([
            'message' => 'Success! Your account has been created.',
        ], 200);
    }
}
