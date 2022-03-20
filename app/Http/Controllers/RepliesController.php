<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;
use App\Reply;
use App\Thread;
use Illuminate\Auth\Access\Gate;

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
        if(Gate::denies('create', new Reply)){
            return response ('
                You are posting too frequently', 422);
        }
        try {

            $this->authorize('create',new Reply);
            $this->validate(request(), [
                'body' => 'required|spamfree',
            ]);
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
            $this->validate(request(), [
                'body' => 'required|spamfree',
            ]);
            $reply->update(['body' => request('body')]);

        } catch (\Exception $e) {
            return response('Sorry your reply could not be saved at this time.', 422);
        }

    }

}
