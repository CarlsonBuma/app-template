<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

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
        
        $data = $request->validate([
            'avatar' => ['nullable', 'mimes:jpg,jpeg,png', 'max:2048'],
            'avatar_delete' => ['required', 'boolean'],
        ]);

        // Set
        $img_src = null;
        $user = User::find(Auth::id());
        $userImgSrc = $user->avatar;
        
        // Delete Avatar
        if($data['avatar_delete'] && $user->avatar) {
            Storage::disk('user')->delete($userImgSrc);
        } 
        
        // Change Image: Existing vs. non existing
        else if ($data['avatar']) {   
            $fileExtension = $request->file('avatar')->extension();
            $img_src = Auth::id() . '-' . Str::random(32) . '.' . $fileExtension;
            if($userImgSrc) Storage::disk('user')->delete($userImgSrc);       
            Storage::putFileAs('public/user', $request->file('avatar'), $img_src);
        }

        $user->avatar = $img_src;
        $user->save();

        return response()->json([
            'message' => $data['avatar_delete']
                ? 'Avatar deleted.'
                : 'Avatar updated.',
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
     *  > Check Password requirements
     *  > Confirm old Password
     *
     * @param Request $request
     * @return void
     */
    public function changePassword(Request $request)
    {
        $user = User::find(Auth::id());
        $data = $request->validate([
            'password_current' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255', 'confirmed', Password::defaults()],
        ]);
            
        // Check Current Password
        if(Hash::check($data['password_current'], $user->password)) {
            
            $user->update([
                'password' => Hash::make($data['password'])
            ]);

            return response()->json([
                'message' => 'Password updated.',
            ], 200);
        }

        return response()->json([
            'message' => 'Ups, the given password is incorrect.',
        ], 422);
    }

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
            if($userImgSrc = $user->avatar) 
                Storage::disk('user')->delete($userImgSrc);
            
            if($cockpitImgSrc = $user->has_cockpit()->first()?->avatar) 
                Storage::disk('cockpit')->delete($cockpitImgSrc);

            // Delete user
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
