<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Filters\ThreadFilters;

class ThreadsController extends Controller
{
    public function __constructor()
    {
        $this->middleware('auth');
    }

    public function index(Channel $channel ,ThreadFilters $filters)
    {


        $threads = $this->getThreads($channel, $filters);
        return view('threads.index', compact('threads'));
    }

    public function create()
    {
        return view('threads.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'title'=>'required',
            'body'=>'required',
            'channel_id'=>'required|exists:channels,id'

        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body')
        ]);

        return redirect($thread->path());
    }

    public function show( $channelId,Thread $thread)
    {

        return view('threads.show',
            [
                'thread'=>$thread,
                'replies'=>$thread->replies
                ]);

    }

    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::filter($filters)->latest();

        if ($channel->exists) {
            $threads = $threads->whereChannelId($channel->id);
        }

        return $threads->get();
    }

}