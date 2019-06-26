<?php

namespace App\Http\Controllers\Forum;

use App\Models\Forum\Activity;
use App\Models\Forum\User;
use App\Http\Controllers\Controller;

class ProfilesController extends Controller
{
    /**
     * Show the user's profile.
     *
     * @param  User $user
     * @return \Response
     */
    public function show(User $user)
    {
        return view('profiles.show', [
            'profileUser' => $user,
            'activities' => Activity::feed($user)
        ]);
    }
}
