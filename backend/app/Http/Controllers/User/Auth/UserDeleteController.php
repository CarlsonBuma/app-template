<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use Laravel\Passport\Token;
use Illuminate\Http\Request;
use App\Http\Classes\FileStorage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserDeleteController extends Controller
{
    /**
     * Delete User Account
     *  > Remove Avatar
     *  > Logout User
     *
     * @param Request $request
     * @return void
     */
    public function deleteAccount(Request $request)
    {
        $user = User::find(Auth::id());
        $data = $request->validate([
            'password' => ['required', 'string', 'max:255'],
        ]);

        if(Hash::check($data['password'], $user->password)) {
            
            // Remove Files
            FileStorage::removeFile(
                FileStorage::$userLocation,
                $user->avatar
            );

            FileStorage::removeFile(
                FileStorage::$cockpitLocation,
                $user->has_cockpit?->avatar
            );

            // Delete user
            Token::where('user_id', $user->id)?->delete();
            $user->delete();
            
            return response()->json([
                'message' => 'Account removed.',
            ], 200);
        }

        return response()->json([
            'message' => 'The given password is incorrect.',
        ], 422);
    }
}
