<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;
use App\Reply;
use App\Thread;

class RepliesController extends Controller
{
    public function __constructor()
    {
        $this->middleware('auth', ['except' => 'index']);
    }


    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(1);
    }

    public function store($channelId, Thread $thread, Spam $spam)
    {

        $this->validate(request(), [
            'body' => 'required',
        ]);

        $spam->detect(request('body'));


        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => Auth()->User()->id
        ]);

        if (request()->expectsJson()) {
            return $reply->load('owner');
        }

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
