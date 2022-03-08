<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;

class RepliesController extends Controller
{
    public function __constructor()
    {
        $this->middleware('auth');
    }

    public function store($channelId, Thread $thread)
    {

        $this->validate(request(), [
            'body' => 'required',
        ]);

        $thread->addReply([
            'body' => request('body'),
            'user_id' => Auth()->User()->id
        ]);

        return redirect()->back();
    }

    public function destroy(Reply $reply)
    {

        $this->authorize('update', $reply);

        $reply->delete();
        if (request()->expectsJson()) {
            return response(['status' => 'Reply Deleted']);
        }
        return back();
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->update(['body' => request('body')]);
    }
}
