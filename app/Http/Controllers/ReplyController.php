<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function __constructor(){
        $this->middleware('auth');
    }
    public function store($channelId, Thread $thread){
        $thread->addReply([
            'body'=>request('body'),
            'user_id'=> Auth()->User()->id
        ]);

        return redirect()->back();
    }
}
