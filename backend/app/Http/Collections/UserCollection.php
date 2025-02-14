<?php

namespace App\Http\Collections;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

abstract class UserCollection
{
    static public function render_public_user(object $user = null): array
    {
        if(!$user) return [];
        return [
            '_type' => 'Collection $publicUser',
            'id' => $user->id,
            'avatar_src' => SELF::render_avatar_src($user->avatar),
            'name' => $user->name
        ];
    }

    /**
     * Render user and its access
     *
     * @param object $user
     * @return array
     */
    static public function render_user(object $user): array
    {
        return [
            '_type' => 'Collection $user',
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => SELF::render_avatar_src($user->avatar),
            'email' => $user->email,
        ];
    }

    /**
     * Render user and its access tokens
     ** Defines what app features, user can access within UI
     * Access tokens are defined according backend logic
     *
     * @param object $user
     * @return array
     */
    static public function render_user_access(object $access): array
    {
        return [
            'access_token' => $access->access_token,
            'quantity' => $access->quantity,
            'expiration_date' => Carbon::parse($access->expiration_date)->format('Y-m-d')
        ];
    }

    /**
     * Undocumented function
     *
     * @param string|null $avatarSrc
     * @return string
     */
    static private function render_avatar_src(string $avatarSrc = null): string
    {
        return $avatarSrc
            ? URL::to(Storage::url('user')) . '/' . $avatarSrc
            : '';
    }
}