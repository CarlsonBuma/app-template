<?php

namespace App\Http\Controllers\Cockpit;

use App\Models\Cockpit;
use Illuminate\Http\Request;
use App\Http\Classes\Modulate;
use App\Http\Classes\FileStorage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Collections\CockpitCollection;


class CockpitProfileController extends Controller
{
    /**
     * Load cockpit profile
     *
     * @return void
     */
    public function loadProfile() 
    {
        $renderedCockpit = CockpitCollection::render_cockpit(
            Cockpit::where('user_id', Auth::id())->first(),
        );

        return response()->json([
            'cockpit' => $renderedCockpit,
            'message' => 'User cockpit loaded.',
        ], 200);
    }

    /**
     * Update publicity
     *  > Flag: $is_public
     *
     * @param Request $request
     * @return void
     */
    public function updatePublicity(Request $request)
    {
        $data = $request->validate([
            'is_public' => ['required', 'boolean'],
        ]);
        
        Cockpit::where('user_id', Auth::id())->update([
            'is_public' => (bool) $data['is_public'],
        ]);

        return response()->json([
            'message' => (bool) $data['is_public'] 
                ? 'Cockpit published.' 
                : 'Cockpit set to private.'
        ], 200);
    }
    
    /**
     * Update avatar image
     *
     * @param Request $request
     * @return void
     */
    public function updateAvatar(Request $request) 
    {
        $cockpit = $request->attributes->get('cockpit');
        $request->validate([
            'file' => ['nullable', 'mimes:jpg,jpeg,png', 'max:10240'],
        ]);

        $imgSrc = FileStorage::storeFile(
            $request->file('file'),
            FileStorage::$cockpitLocation,
            $cockpit->avatar,
            $cockpit->id,
        );
        
        $cockpit->avatar = $imgSrc;
        $cockpit->save();

        return response()->json([
            'message' => 'Avatar updated.',
        ], 200);
    }

    /**
     * Update Credentials
     *
     * @param Request $request
     * @return void
     */
    public function updateName(Request $request) 
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Cockpit::where('user_id', Auth::id())->update([
            'name' => $data['name'],
        ]);

        return response()->json([
            'message' => 'Name updated.',
        ], 200);
    }

    /**
     * Update about
     *
     * @param Request $request
     * @return void
     */
    public function updateAbout(Request $request) 
    {
        $data = $request->validate([
            'about' => ['nullable', 'string', 'max:1999'],
        ]);

        Cockpit::where('user_id', Auth::id())->update([
            'about' => $data['about'],
        ]);

        return response()->json([
            'message' => 'About has been updated.',
        ], 200);
    }

    /**
     * Update impressumg
     *
     * @param Request $request
     * @return void
     */
    public function updateImpressum(Request $request) 
    {
        $data = $request->validate([
            'website' => ['nullable', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:999'],
        ]);

        $websiteSanitized = Modulate::sanitizeLink($data['website']);
        Cockpit::where('user_id', Auth::id())->update([
            'website' => $websiteSanitized,
            'contact' => $data['contact'],
        ]);

        if($data['website'] && !$websiteSanitized) {
            return response()->json([
                'message' => 'Invalid link.',
            ], 422);
        }

        return response()->json([
            'message' => 'Impressum updated.',
        ], 200);
    }

    /**
     * Update tags
     *
     * @param Request $request
     * @return void
     */
    public function updateTags(Request $request) 
    {
        $data = $request->validate([
            'tags' => ['nullable', 'array'],
        ]);

        Cockpit::where('user_id', Auth::id())->update([
            'tags' => $data['tags'],
        ]);

        return response()->json([
            'message' => 'Bulletpoints updated.',
        ], 200);
    }
}
