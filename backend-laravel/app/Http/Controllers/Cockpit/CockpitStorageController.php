<?php

namespace App\Http\Controllers\Cockpit;

use Exception;
use Illuminate\Http\Request;
use App\Models\CockpitStorage;
use App\Http\Controllers\Controller;
use App\Providers\CloudStorageProvider;
use App\Http\Collections\CockpitStorageCollection;

class CockpitStorageController extends Controller
{
    protected string $prefixFlyer = '';
    protected string $prefixGallery = '';

    function __construct() 
    {
        $this->prefixFlyer = CockpitStorageCollection::$prefixFlyer;
        $this->prefixGallery = CockpitStorageCollection::$prefixGallery;
    }

    /**
     * Indexed Pagiation
     *
     * @param Request $request
     * @return void
     */
    public function readGallery(Request $request) 
    {
        $cockpit = $request->attributes->get('cockpit');
        $data = $request->validate([
            'limit' => ['required', 'numeric'],
            'latest_file_id' => ['nullable', 'numeric'],   // Pagination
        ]);

        $query = CockpitStorage::where([
            'cockpit_id' => $cockpit->id,
            'prefix' => $this->prefixGallery,
        ])->orderBy('created_at', 'desc');

        // Pagination
        if (isset($data['latest_file_id']) && $data['latest_file_id']) {
            $latestFile = CockpitStorage::find($data['latest_file_id']);
            if ($latestFile) {
                $query->where('created_at', '<', $latestFile->created_at);
            }
        }

        // Set Query
        $gallery = $query->limit($data['limit'])->get();
        $latestFileID = $gallery->last()?->id;
        $hasMore = $gallery->count() === (int) $data['limit'];
        $galleryCollection = $gallery->map(fn ($file) => CockpitStorageCollection::render_cockpit_storage_file($file));

        return response()->json([
            'gallery' => $galleryCollection,
            'latest_file_id' => $latestFileID,
            'has_more' => $hasMore,
        ], 200);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function uploadFile(Request $request)
    {
        $cockpit = $request->attributes->get('cockpit');
        $request->validate([
            'file' => ['required', 'mimes:jpg,jpeg,png', 'max:20480'],
        ]);

        // Upload File
        try {
            $Storage = new CloudStorageProvider($cockpit);
            $store = $Storage->uploadNewFile($request->file('file'), $this->prefixGallery);

            return response()->json([
                'file' => CockpitStorageCollection::render_cockpit_storage_file($store),
                'message' => 'Image uploaded.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function renameFile(Request $request)
    {
        $file = $request->attributes->get('file');
        $data = $request->validate([
            'storage_id' => ['required', 'numeric'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $file->update([
            'name' => $data['name']
        ]);

        return response()->json([
            'message' => 'Filename updated.'
        ], 200);  
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function publishFile(Request $request)
    {
        $file = $request->attributes->get('file');
        $data = $request->validate([
            'storage_id' => ['required', 'numeric'],
        ]);

        $file->update([
            'is_public' => true
        ]);

        return response()->json([
            'message' => 'Public file access.'
        ], 200);  
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function unpublishFile(Request $request)
    {
        $file = $request->attributes->get('file');
        $data = $request->validate([
            'storage_id' => ['required', 'numeric'],
        ]);

        $file->update([
            'is_public' => false
        ]);

        return response()->json([
            'message' => 'Private file access.'
        ], 200);  
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function removeFile(Request $request)
    {
        $cockpit = $request->attributes->get('cockpit');
        $file = $request->attributes->get('file');
        $data = $request->validate([
            'storage_id' => ['required', 'numeric'],
        ]);

        try {
            $Storage = new CloudStorageProvider($cockpit);
            $Storage->removeFile($file->id);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }

        return response()->json([
            'message' => 'File removed.'
        ], 200);  
    }
}
