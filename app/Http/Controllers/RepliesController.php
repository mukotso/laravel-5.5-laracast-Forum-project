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

    public function store($channelId, Thread $thread)
    {
        try {
            $this->validateReply();
            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => Auth()->User()->id
            ]);

        } catch (\Exception $e) {
            return response('Sorry your reply could not be saved at this time.', 422);
        }
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

    public function update(Reply $reply, Spam $spam)
    {
        $this->authorize('update', $reply);
        try {
            $this->validateReply();
            $reply->update(['body' => request('body')]);

        } catch (\Exception $e) {
            return response('Sorry your reply could not be saved at this time.', 422);
        }

    }

    function validateReply(Spam $spam)
    {
        $this->validate(request(), [
            'body' => 'required',
        ]);

        $spam->detect(request('body'));
    }
}
