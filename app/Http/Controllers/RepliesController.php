<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function __constructor(){
        $this->middleware('auth');
    }
    public function store($channelId, Thread $thread){

        $this->validate(request,[
            'body'=>'required',

        ]);

        $thread->addReply([
            'body'=>request('body'),
            'user_id'=> Auth()->User()->id
        ]);

        return redirect()->back();
    }
}
