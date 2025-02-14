<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\Access\AccessHandler;

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
        $accessToken = AccessHandler::$tokenCockpit;
        if($userAccess = AccessHandler::checkUserAccessByToken(Auth::id(), $accessToken)) {
            $cockpit = $userAccess->belongs_to_user->has_cockpit;
            $request->attributes->set('cockpit', $cockpit);
            return $next($request);  
        } 

        return response()->json([
            'access_token' => $accessToken,
            'status' => 'no_access_to_feature',
            'message' => 'No access to feature.'
        ], 401);  
    }
}
