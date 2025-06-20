<?php

namespace App\Http\Classes;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


abstract class FileStorage
{
    static public $userLocation = 'user';
    static public $cockpitLocation = 'cockpit';
    static public $eventLocation = 'events';

    /**
     * Store file
     *
     * @param object|null $file
     * @param string $location
     * @param string|null $currentFileLink
     * @param string $prefix
     * @return string|null
     */
    static public function storeFile(?object $file, string $location, ?string $currentFileLink, string $prefix = 'x'): ?string
    {
        $imgSrcLink = null;

        // Remove current file
        if($currentFileLink) {
            SELF::removeFile($location, $currentFileLink);
        }
        
        // Add file
        if($fileExtension = $file?->extension()) { 
            $srcLink = $prefix . '-' . Str::random(32) . '.' . $fileExtension;
            Storage::disk($location)->putFileAs('', $file, $srcLink);
            $imgSrcLink = $srcLink;
        }

        return $imgSrcLink;
    }

    /**
     * Undocumented function
     *
     * @param string $location
     * @param string|null $currentFileLink
     * @return void
     */
    static public function removeFile(string $location, ?string $currentFileLink)
    {
        if($location && $currentFileLink) {
            Storage::disk($location)->delete($currentFileLink);
        }
    }
}
