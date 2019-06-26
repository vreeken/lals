<?php

namespace App\Http\Controllers\Forum;

use App\Models\Forum\Reply;
use App\Http\Controllers\Controller;

class BestRepliesController extends Controller
{
    /**
     * Mark the best reply for a thread.
     *
     * @param  Reply $reply
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Reply $reply)
    {
        $this->authorize('update', $reply->thread);

        $reply->thread->markBestReply($reply);
    }
}
