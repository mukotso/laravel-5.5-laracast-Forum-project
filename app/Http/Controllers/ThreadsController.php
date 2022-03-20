<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Thread;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{
    public function __constructor()
    {
        $this->middleware('auth');
    }

    public function index(Channel $channel, ThreadFilters $filters)
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
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id'

        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body')
        ]);

        return redirect($thread->path())
            ->with('flash', 'Your thread has been published');
    }

    public function show($channelId, Thread $thread)
    {

        $key = sprintf("users.%s.visits.%s", auth()->id(), $thread->id);
        cache()->forever($key, Carbon::now());
        //return $thread->load('replies.favorite');
//       return  $thread->append('isSubscribedTo');

        return view('threads.show', compact('thread'));

    }

    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads = $threads->whereChannelId($channel->id);
        }

        return $threads->get();
    }

    public function destroy($channelSlug, Thread $thread)
    {
        $this->authorize('update', $thread);
//        if($thread->user_id != auth()->id){
//
//            abort(403,'You do not have permission to do this !!!');
//        }
        //        $this->authorize('update', $thread);

        //        $thread->replies()->delete();
        $thread->delete();
        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads');

    }

}