<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\CockpitStorage;

class AccessFile
{
    /**
     * Verify access for the "Cockpit" feature.
     * See folder "\Controllers\Cockpit"
     *
     * @param Request $request
     * @param Closure $next
     * @return void
     */
    public function handle(Request $request, Closure $next)
    {   
        $cockpit = $request->attributes->get('cockpit');
        $data = $request->validate([
            'storage_id' => ['required', 'numeric'],
        ]);

        $storage = CockpitStorage::where([
            'id' => $data['storage_id'],
            'cockpit_id' => $cockpit->id
        ])->first();

        if($storage) {
            $request->attributes->set('file', $storage);
            return $next($request);  
        } 

        return response()->json([
            'message' => 'File does not exist.'
        ], 422);  
    }
}
