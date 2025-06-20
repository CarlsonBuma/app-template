<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\AppNewsfeed;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminNewsfeedController extends Controller
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function loadEntries()
    {
        return response()->json([
            'newsfeed' => AppNewsfeed::orderBy('created_at', 'desc')->get()
        ], 200);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1999'],
            'type' => ['required', 'string', 'max:255'],
        ]);

        $entryID = AppNewsfeed::create([
            'title' => $data['title'],
            'version' => $data['version'],
            'description' => $data['description'],
            'type' => $data['type'],
        ])->id;

        return response()->json([
            'entry_id' => $entryID,
            'message' => 'Entry created.'
        ], 200);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'numeric'],
            'title' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1999'],
            'type' => ['required', 'string', 'max:255'],
        ]);

        AppNewsfeed::find($data['id'])->update([
            'title' => $data['title'],
            'version' => $data['version'],
            'description' => $data['description'],
            'type' => $data['type'],
        ]);

        return response()->json([
            'message' => 'Entry updated.'
        ], 200);
    }

    /**
     * Undocumented function
     *
     * @param integer $id
     * @return void
     */
    public function delete(int $id)
    {
        try {
            if(!$id) throw new Exception('ID is required.');
            AppNewsfeed::find((int) $id)->delete();
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Entry deleted.'
        ], 200);
    }
}
