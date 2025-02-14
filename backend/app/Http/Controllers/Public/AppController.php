<?php

namespace App\Http\Controllers\Public;

use App\Models\AppNewsfeed;
use App\Models\AppCountries;
use App\Models\AppLanguages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppController extends Controller
{
    /**
     * Public Newsfeed
     * Indexing newsfeed response by latest position
     * 
     * @param integer $index
     * @return void
     */
    public function loadNewsfeedIndex(Request $request)
    {
        $entriesPerRequest = 12;
        $data = $request->validate([
            'index' => ['required', 'numeric'],
        ]);
        
        $newsfeed = AppNewsfeed::orderBy('created_at', 'desc')
            ->skip($data['index'])
            ->take($entriesPerRequest + 1)
            ->get();
        
        // Check last entry
        $isLastEntry = $newsfeed->count() <= $entriesPerRequest;
        if(!$isLastEntry) $newsfeed->pop();
        
        return response()->json([
            'newsfeed' => $newsfeed,
            'is_last_entry' => $isLastEntry
        ], 200);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function loadPublicLanguages() 
    {
        return response()->json([
            'language' => AppLanguages::where('is_public', true)->get(),
            'message' => 'Language loaded.',
        ], 200);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function loadPublicCountries() 
    {
        return response()->json([
            'countries' => AppCountries::where('is_public', true)->get(),
            'message' => 'Countries loaded.',
        ], 200);
    }
}
