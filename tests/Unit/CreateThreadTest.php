<?php

namespace Tests\Unit;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function test_guest_may_not_create_thread(){
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $thread=factory('App\Thread')->make();
        $this->post('/threads',$thread->toArray());

    }
//
//    public function test_guest_cannot_see_Create_thread_page(){
//        $this->post('/threads/create')
//            ->assertRedirect('/login');
//
//    }

    public function test_an_authenticated_user_can_Create_new_thread ()
    {
        //Given we have a signed in user
        $this->actingAs(factory('App\User')->create());
        //when we hit the end point to create a thread
        $thread=factory('App\Thread')->create();

        $this->post('/threads',$thread->toArray());
        $this->get($thread->path())->assertSee($thread->title);

    }
}
