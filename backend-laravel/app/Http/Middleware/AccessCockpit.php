<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessCockpit
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
        if($user = Auth::guard('api')->user()) {
            $request->attributes->set('cockpit', $user->has_cockpit);
            return $next($request);  
        }

        return response()->json([
            'status' => 'no_access_to_feature',
            'message' => 'No access to feature.'
        ], 401);  
    }
}
