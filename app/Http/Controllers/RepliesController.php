<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Inspections\Spam;
use App\Notifications\youWereMentioned;
use App\Reply;
use App\Thread;
use App\User;

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

    public function store($channelId, Thread $thread, CreatePostRequest $form)
    {

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => Auth()->User()->id
        ]);

        preg_match_all('/\@([^\s\.]+)/', $reply->body, $matches);
        $names = $matches[1];
        foreach ($names as $name) {
            $user = User::whereName($name)->first();
            if ($user) {
                $user->notify(new youWereMentioned($reply));
            }

        }
        return $reply->load('owner');
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
