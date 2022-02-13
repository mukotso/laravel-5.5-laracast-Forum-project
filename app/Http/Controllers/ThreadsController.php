<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadsController extends Controller
{
    public function __constructor()
    {
        $this->middleware('auth');
    }

    public function index(Channel $channel)
    {
        if($channel->exists){
            $threads= $channel->threads()->latest();
        }else{

            $threads = Thread::latest();
        }

        if($username =request('by')){
            $user=\App\User::where('name', $username)->firstOrFail();

            $threads->where('user_id',$user->id);
        }
        $threads = $threads->get();
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

        return view('threads.show', compact('thread'));
    }

}