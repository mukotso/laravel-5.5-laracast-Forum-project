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
        $thread=make('App\Thread');
        $this->post('/threads',$thread->toArray());

    }

    public function test_guest_cannot_see_Create_thread_page(){
        $this->withExceptionHandling()
        ->get('/threads/create')
            ->assertRedirect('/login');

    }

    public function test_an_authenticated_user_can_Create_new_thread ()
    {
        //Given we have a signed in user
        $this->signIn();


        //when we hit the end point to create a thread
        $thread=create('App\Thread');

//        $this->post('/threads',$thread->toArray());
        $this->get($thread->path())->assertSee($thread->title);

    }

    public function a_thread_requires_a_title(){

        $this->publishThread(['title' =>null])
            ->assertSessionHasErrors('title');

    }
    public function a_thread_requires_a_body(){

        $this->publishThread(['body' =>null])
            ->assertSessionHasErrors('body');

    }

    public function a_thread_requires_a_valid_channel(){

        factory ('App\Channel',2)->create();
        $this->publishThread(['channel_id' =>null])
            ->assertSessionHasErrors('channel_id');


        $this->publishThread(['channel_id' =>999])
            ->assertSessionHasErrors('channel_id');

    }


    public function publishThread($overrides =[]){
        $this->withExceptionHandling()->signIn();
       $thread =make('App\Thread',$overrides);
     return  $this->post('/threads', $thread->toArray());

    }
}
