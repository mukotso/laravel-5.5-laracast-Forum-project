<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;
    public function test_reply_has_an_owner()
    {
        $reply = factory('App\Reply')->create();
        $this->assertInstanceOf(User::class, $reply->owner);

    }

    public function test_a_thread_has_a_user(){
        //given we have a thread

        $thread=factory('App\Thread')->create();

        //we should see replies when we visit that page
        $this->assertInstanceOf(User::class,$thread->creator);
    }
}
