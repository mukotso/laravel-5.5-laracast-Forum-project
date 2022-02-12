<?php

namespace Tests\Feature;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;
    public function test_an_authenticated_user_may_participate_in_forum_thread()
    {
        //given we have an authenticated user
//        $this->signIn();
        $this->be(factory('App\User')->create());
        //and an existing thread
        $thread=factory('App\Thread')->create();
//        when a user adds areply to the thread
        $reply=factory('App\Reply')->make();
        $this->post($thread->path().'/replies',$reply->toArray());
//    The reply should be visible in the page
        $this->get($thread->path())
            ->assertSee($reply->body);
    }

//    public function test_unauthenticated_user_may_not_add_replies()
//    {
//
//        $this->withoutExceptionHandling();
//        $this->expectException('Illuminate\Auth\Authentication');
//        $this->post('/threads/some-channel/1/replies', [])
//            ->assertRedirect('/login');
//    }

}
