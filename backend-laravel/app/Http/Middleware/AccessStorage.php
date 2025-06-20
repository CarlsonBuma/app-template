<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Access\AccessHandler;
use Closure;
use Illuminate\Http\Request;
use App\Models\CockpitStorage;

class AccessStorage
{
    public function handle(Request $request, Closure $next)
    {   
        // Attributes
        $cockpit = $request->attributes->get('cockpit');
        $access = AccessHandler::getUserAccessByToken($cockpit->user_id, AccessHandler::$accessCockpitToken);
        $maxStorageSizeGB = $access->limit_storage ?? AccessHandler::$accessCockpitFreeStorage;
        $maxStorageInBytes = $maxStorageSizeGB * 1024 * 1024 * 1024;
        $maxAmountOfFiles = AccessHandler::$accessCockpitStorageFilesLimit;

        // Amount of Storage
        $storageSize = CockpitStorage::where('cockpit_id', $cockpit->id)->sum('size_bytes');
        if ($storageSize > $maxStorageInBytes) {
            return response()->json([
                'storage_size' => $storageSize,
                'message' => 'Storage size exceeds ' . $maxStorageSizeGB . 'GB limit.',
            ], 422);
        }

        // Amount of Fils
        $storageCount = CockpitStorage::where('cockpit_id', $cockpit->id)->count();
        if ($storageCount > $maxAmountOfFiles) {
            return response()->json([
                'storage_count' => $storageCount,
                'message' => 'Storage count exceeds limit of ' . $maxAmountOfFiles . ' files.',
            ], 422);
        }

        return $next($request);
    }
}
