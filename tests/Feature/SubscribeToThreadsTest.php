<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    function a_user_can_subscribe_to_threads()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $this->post($thread->path() . '/subscriptions');
        $this->assertCount(1,$thread->fresh()->subscriptions);
//        $thread->addReply([
//            'user_id'=>auth()->id,
//            'body'=>'some reply here'
//        ]);
//        $this->assertCount(1,auth()->user()->fresh()->notifications);
    }

    function a_user_unsubscribe_from_thread(){
        $this->signIn();
        $thread = create('App\Thread');
        $thread->subscribe();

        $this->delete($thread->path() . '/subscriptions');

        $this->assertCount(0,$thread->subscriptions);
    }

}

