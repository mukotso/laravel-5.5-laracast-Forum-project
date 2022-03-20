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

    public function test_a_thread_requires_a_title(){

        $this->publishThread(['title' =>null])
            ->assertSessionHasErrors('title');

    }
    public function a_thread_requires_a_body(){

        $this->publishThread(['body' =>null])
            ->assertSessionHasErrors('body');

    }

    public function test_a_thread_requires_a_valid_channel(){

        factory ('App\Channel',2)->create();
        $this->publishThread(['channel_id' =>null])
            ->assertSessionHasErrors('channel_id');


        $this->publishThread(['channel_id' =>999])
            ->assertSessionHasErrors('channel_id');

    }


    public function test_publishThread($overrides =[]){
        $this->withExceptionHandling()->signIn();
       $thread =make('App\Thread',$overrides);
     return  $this->post('/threads', $thread->toArray());

    }


    public function test_unauthorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $this->delete($thread->path())
            ->assertRedirect('/login');

        $this->signIn();
        $this->delete($thread->path())
            ->assertStatus(403);
    }

    public function test_authorized_users_may_delete_threads()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $this->json('DELETE', $thread->path())
            ->assertStatus(204);
        $this->assertEquals(0,Activity::count());

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
              'subject_type'=> get_class($thread)
            ]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type'=> get_class($thread)
        ]);
    }
}
