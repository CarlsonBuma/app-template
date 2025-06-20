<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use App\Models\Cockpit;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Classes\Modulate;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends Controller
{
    /**
     * Redirect to Google
     * !Keine Web-Middleware, da SPA Appraoch
     * 
     *
     * @param Request $request
     * @return void
     */
    public function redirect(Request $request)
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Callback from Google
     * !Keine Web-Middleware, da SPA Appraoch
     * 
     * Note:
     * In case user has manually created its account, and afterwards Login with Google,
     * We are sure, the email already belongs to google account, by Socialite validation.
     * Therefore, only email matters instead of google_id
     *  > Email impersionation is not possible.
     *
     * @param Request $request
     * @return void
     */
    public function callback(Request $request)
    {
        // Generate secure token
        $token = Str::random(64);
        $redirectURL = config('app.url') . "/authenticate";

        try {
            // Setup HTTP client with optional SSL verification (useful in dev)
            Socialite::driver('google')->setHttpClient(new \GuzzleHttp\Client([
                'verify' => !config('app.unsafe_http_request'),
            ]));

            // Safety check: email must exist
            $googleUser = Socialite::driver('google')->stateless()->user();
            if (empty($googleUser->email || $googleUser->id)) {
                return redirect($redirectURL)->with('error', 'Google authentication failed: Email missing.');
            }

            $authenticationLink = Modulate::signedLink('authenticate', [
                'email' => $googleUser->email,
                'token' => $token
            ]);

            // Google User
            if($existingUser = User::where('google_id', $googleUser->id)->first()) {
                $existingUser->update([
                    'google_avatar' => $googleUser->avatar,
                    'token' => $token,
                ]);

                return redirect($authenticationLink);
            }
            
            // New Account
            $existingUser = User::where('email', $googleUser->email)->first();
            if(!$existingUser) {
                $user = User::create([
                    'email' => $googleUser->email,
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'google_avatar' => $googleUser->avatar,
                    'password' => Hash::make(Str::random(64)),
                    'email_verified_at' => now(),
                    'token' => $token,
                ]);

                // Set dependencies
                Cockpit::firstOrCreate([
                    'user_id' => $user->id
                ]);

                // Authenticate Redirect
                return redirect($authenticationLink);
            }

            // Connect google account with existing account
            // Email must not be verified -> possible Impersination
            if($existingUser && !$existingUser->email_verified_at) {
                $existingUser->update([
                    'google_id' => $googleUser->id,
                    'google_avatar' => $googleUser->avatar,
                    'email_verified_at' => now(),
                    'token' => $token
                ]);

                return redirect($authenticationLink);
            }

            // Account can not be linked to Google
            Log::channel('app')->info("GoogleLoginController: Existing account not linked to Google: {$existingUser->email}");
            return redirect($redirectURL)->with('error', 'Account not linked to a Google Account.');

        } catch (\Throwable $e) {
            Log::channel('app')->error('Google OAuth callback failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect($redirectURL)->with('error', 'Authentication failed. Please try again.');
        }
    }
}
