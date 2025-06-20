<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function loadUser()
    {
        return response()->json([
            'message' => 'User loaded.',
        ], 200);
    }
}
