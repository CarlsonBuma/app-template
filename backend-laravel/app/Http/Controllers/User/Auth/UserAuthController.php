<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Collections\UserCollection;
use App\Http\Controllers\Access\AccessHandler;


class UserAuthController extends Controller
{
    /**
     * Default data for our Client-UI-Handling
     *
     * @return void
     */
    public function getUser()
    {
        $user = Auth::user();
        $userAccess = AccessHandler::getLatestUserAccesses($user->id)->map(function($access) {
            return UserCollection::render_user_access($access);
        });

        return response()->json([
            'user' => UserCollection::render_user($user),
            'access' => $userAccess
        ], 200);
    }
    
    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function authenticateUser(String $email, String $token, Request $request)
    {
        if( // Verify Signature & email token
            $request->hasValidSignature() 
            && $user = User::where([
                'email' => $email,
                'token' => $token
            ])->first()
        ) {
            $token = $user->createToken('client-access')->accessToken;
            $user->email_verified_at = $user->email_verified_at ?? now();
            $user->token = null;
            $user->save();

            return response()->json([
                'token' => $token,
                'message' => 'Session started.'
            ], 200);
        }

        return response()->json([
            'message' => 'Link has been expired.',
        ], 422);
    }

    /**
     * User Login 
     *  > Attemps-Middleware: throttle:6,1
     *  > Check Token: $email_verified_at
     *  > Start Session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginUser(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials do not match our records.',
            ], 422);
        }

        if (is_null($user->email_verified_at)) {
            return response()->json([
                'status' => 'email_not_verified',
                'email' => $user->email,
                'message' => 'Please verify your email before accessing your account.',
            ], 401);
        }

        $token = $user->createToken('client-access')->accessToken;

        return response()->json([
            'token' => $token,
            'message' => 'Session started.'
        ], 200);
    }


    /**
     * Remove session
     *
     * @return void
     */
    public function logoutUser()
    {
        $user = (object) Auth::user();
        $user->token = null;
        $user->save();
        
        $user->token()->delete();
        
        return response()->json([
            'message' => 'Session closed.'
        ], 200);
    }
}
