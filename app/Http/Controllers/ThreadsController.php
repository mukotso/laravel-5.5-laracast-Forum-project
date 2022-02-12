<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{
    public function __constructor()
    {
        $this->middleware('auth')->only('store');
    }

    public function index()
    {
        $threads = Thread::latest()->get();
        return view('threads.index', compact('threads'));
    }

    public function create()q
    {
        return view('threads.create');
    }

    public function store(Request $request)
    {

        $thread = Thread::create([
            'user_id' => auth()->id(),
//            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body')
        ]);

        return redirect($thread->path());
    }

    public function show( Thread $thread)
    {

        return view('threads.show', compact('thread'));
    }

}