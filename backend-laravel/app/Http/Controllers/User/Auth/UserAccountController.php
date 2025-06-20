<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Passport\Token;
use Illuminate\Http\Request;
use App\Http\Classes\Modulate;
use App\Mail\SendPasswordReset;
use App\Http\Classes\FileStorage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserAccountController extends Controller
{
    /**
     * Change Avatar
     *  > Update Avatar
     *      > Delete Old Avatar
     *      > Add new Avatar
     *      > Link new Avatar with DB
     *  > Delete Avatar
     *      > Delete Old Avatar 
     *
     * @param Request $request
     * @return void
     */
    public function changeAvatar(Request $request) {
        
        $request->validate([
            'file' => ['nullable', 'mimes:jpg,jpeg,png', 'max:10240'],
        ]);

        $user = User::find(Auth::id());
        $imgSrc = FileStorage::storeFile(
            $request->file('file'),
            FileStorage::$userLocation,
            $user->avatar,
            $user->id,
        );
        
        $user->avatar = $imgSrc;
        $user->google_avatar = null;
        $user->save();

        return response()->json([
            'message' => 'Avatar updated.',
        ], 200);
    }

    /**
     * Change Username
     *
     * @param Request $request
     * @return void
     */
    public function changeName(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:21'],
        ]);

        User::find(Auth::id())->update([
            'name' => $data['name'],
        ]);

        return response()->json([
            'message' => 'Username updated.',
        ], 200);
    }

    /**
     * Update Password
     * via Email Token Request.
     *
     * @param Request $request
     * @return void
     */
    public function changePassword()
    {
        $token = Str::random(64);
        $user = User::find(Auth::id());
        $verificationLink = Modulate::signedLink('password.reset', [
            'email' => $user->email,
            'token' => $token
        ]);

        // Send Mail
        Mail::to($user)->send(new SendPasswordReset($verificationLink, $user));
        $user->token = $token;
        $user->save();

        $user = (object) Auth::user();
        $user->token()->delete();

        return response()->json([
            'message' => 'Reset Passwort link sent to email.',
        ], 200); 
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function invalidateTokens()
    {
        Token::where('user_id', Auth::id())?->delete();
        return response()->json([
            'message' => 'Tokens removed.',
        ], 200); 
    }

    /**
     * Ref. GoogleLoginController
     * Add Google Login
     *
     * @return void
     */
    public function invalidateEmail()
    {
        User::find(Auth::id())->update([
            'email_verified_at' => null,
            'google_id' => null,
            'google_avatar' => null,
        ]);

        $user = (object) Auth::user();
        $user->token()->delete();

        return response()->json([
            'message' => 'Please verify your email again.',
        ], 200); 
    }
}
