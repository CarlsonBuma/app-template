<?php

namespace App\Http\Collections;

use App\Models\CockpitStorage;

abstract class CockpitStorageCollection
{   
    // GCS Bucket Types
    public static $prefixLogo = 'logo';
    public static $prefixFlyer = 'flyer';
    public static $prefixGallery = 'gallery';

    /**
     * Undocumented function
     *
     * @param CockpitStorage|null $store
     * @return array|null
     */
    static public function render_cockpit_storage_file(?CockpitStorage $store): ?array
    {
        if(!$store) return null;
        return [
            '_type' => 'Collection $CockpitStore',
            'id' => $store->id,
            'cockpit_id' => $store->cockpit_id,
            'is_public' => $store->is_public,
            'file_id' => $store->file_id,
            'prefix' => $store->prefix,
            'name' => $store->name,
            'url' => $store->url,
            'url_download' => $store->url_download,
            'content_type' => $store->content_type,
            'size_bytes' => $store->size_bytes,
        ];
    }

    /**
     * Undocumented function
     *
     * @param CockpitStorage|null $store
     * @return array|null
     */
    static public function render_public_storage_file(?CockpitStorage $store): ?array
    {
        if(!$store) return null;
        return [
            '_type' => 'Collection $PublicStore',
            'id' => $store->id,
            'cockpit_id' => $store->cockpit_id,
            'is_public' => $store->is_public,
            'name' => $store->name,
            'file_id' => $store->file_id,
            'prefix' => $store->prefix,
            'url' => $store->url,
            'url_download' => $store->url_download,
            'content_type' => $store->content_type,
        ];
    }
}