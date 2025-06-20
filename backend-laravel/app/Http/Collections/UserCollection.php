<?php

namespace App\Http\Collections;

use App\Http\Classes\FileStorage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

abstract class UserCollection
{
    /**
     * Undocumented function
     *
     * @param object|null $user
     * @return array
     */
    static public function render_public_user(?object $user): array
    {
        if(!$user) return [];
        return [
            '_type' => 'Collection $publicUser',
            'id' => $user->id,
            'avatar_src' => SELF::render_avatar_src($user),
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
            'avatar' => SELF::render_avatar_src($user),
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
            'limit_storage' => (float) $access->limit_storage,
            'expiration_date' => $access->expiration_date
        ];
    }

    /**
     * Undocumented function
     *
     * @param object $user
     * @return string
     */
    static private function render_avatar_src(object $user): string
    {
        return $user->avatar
            ? URL::to(Storage::url(FileStorage::$userLocation)) . '/' . $user->avatar
            : $user->google_avatar ?? '';
    }
}