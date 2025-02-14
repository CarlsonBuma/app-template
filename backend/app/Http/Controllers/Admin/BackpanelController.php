<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;

class BackpanelController extends Controller
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function loadDashboard()
    {
        return response()->json([
            'users' => User::where('email_verified_at', '!=', null)->count()
        ], 200);
    }
}
