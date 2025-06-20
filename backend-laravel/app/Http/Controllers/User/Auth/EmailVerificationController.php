<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Classes\Modulate;
use App\Mail\SendEmailVerification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class EmailVerificationController extends Controller
{
    /**
     * Request email verificaton
     * > Generate URL, with Token 
     * > Send verification link
     *
     * @param Request $request
     * @return void
     */
    public function sendToken(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $user = User::where('email', $data['email'])->first();
        if ($user && !$user->email_verified_at) {
            
            // Create Link
            $token = Str::random(64);
            $verificationToken = Modulate::signedLink('email.verification', [
                'email' => $user->email,
                'token' => $token
            ]);

            // Send email
            Mail::to($user)->send(new SendEmailVerification($verificationToken, $user));
            $user->token = $token;
            $user->save();
        }

        return response()->json([
            'message' => 'Token has been sent to your email.',
            'fallback' => $verificationToken,
        ], 200);
    }

    /**
     * Verify Email
     *  > Validate URL & Token
     *  > Check if transfer-email is unique
     *  > Change email & update email_verified_at
     *  > Login
     *
     * @param String $id
     * @param String $hash
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
                'email_verified_at' => null,
                'token' => $token
            ])->first()
        ) {
            // Verify user
            $user->password = Hash::make($data['password']);
            $user->email_verified_at = now();
            $user->token = null;
            $user->save();

            // Auth
            $token = $user->createToken('user-client-access')->accessToken;
            return response()->json([
                'token' => $token,
                'message' => 'Welcome! Your email has been verified.'
            ], 200);
        }

        return response()->json([
            'message' => 'Link has been expired.',
        ], 422);
    }
}
