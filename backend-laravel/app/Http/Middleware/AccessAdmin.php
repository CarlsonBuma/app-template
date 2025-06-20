<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Access\AccessHandler;

class AccessAdmin
{
    /**
     * Verify access for the "Admin" feature.
     * See folder "\Controllers\Admin"
     *
     * @param Request $request
     * @param Closure $next
     * @return void
     */
    public function handle(Request $request, Closure $next)
    {   
        $accessToken = AccessHandler::$accessAdminToken;
        if(AccessHandler::getUserAccessByToken(Auth::id(), $accessToken)) 
            return $next($request);   

        return response()->json([
            'access_token' => $accessToken,
            'status' => 'no_access_to_feature',
            'message' => 'No access to feature.'
        ], 401);   
    }
}
