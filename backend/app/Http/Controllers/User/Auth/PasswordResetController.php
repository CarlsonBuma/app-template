<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Classes\Modulate;
use App\Mail\SendPasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class PasswordResetController extends Controller
{
    /**
     * Send Reset Email
     *  > Generate URL, with Token 
     *  > Send verification link to new email
     *
     * @param Request $request
     * @return void
     */
    public function sendToken(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        if($user = User::where('email', $data['email'])->first()) {

            // Send verification Link
            $token = Str::random(64);
            $verificationLink = Modulate::signedLink('password.reset', [
                'email' => $user->email,
                'token' => $token
            ]);

            // Start verification process
            Mail::to($user)->send(new SendPasswordReset($verificationLink, $user));
            $user->token = $token;
            $user->save();
        }

        return response()->json([
            'fallback' => $verificationLink,
            'message' => 'The token has been sent to your email.',
        ], 200); 
    }

    /**
     * Create new user passwort
     *  > Validate URL & Token
     *  > Update current password (hashed)
     *  > Login
     *
     * @param Request $request
     * @return void
     */
    public function verifyToken(String $email, String $token, Request $request) 
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'max:255', 'confirmed', Password::defaults()],
        ]);
        
        if( // Verify Signature & email token
            $request->hasValidSignature() 
            && $user = User::where([
                'email' => $email,
                'token' => $token
            ])->first()
        ) {
            // Update User
            $user->password = Hash::make($data['password']);
            $user->token = null;
            $user->email_verified_at = $user->email_verified_at ?? now();
            $user->save();

            // Auth
            $token = $user->createToken('user-client-access')->accessToken;
            return response()->json([
                'token' => $token,
                'message' => 'Your password has been updated.'
            ], 200);
        }

        return response()->json([
            'message' => 'Link has been expired.',
        ], 422);
    }
}
