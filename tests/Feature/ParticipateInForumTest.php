<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    public function test_an_authenticated_user_may_participate_in_forum_thread()
    {
        //given we have an authenticated user
        $this->signIn();
//        $this->be(create('App\User'));
        //and an existing thread
        $thread = create('App\Thread');
//        when a user adds areply to the thread
        $reply = make('App\Reply');
        $this->post($thread->path() . '/replies', $reply->toArray());
//    The reply should be visible in the page
        $this->get($thread->path());
        $this->assertDatabaseHas('replies',['body'=>$reply->body]);
        $this->assertEquals(1,$thread->fresh()->replies_count);
    }

    public function test_unauthenticated_user_may_not_add_replies()
    {

        $this->withExceptionHandling();
        $this->post('/threads/some-channel/1/replies', [])
            ->assertRedirect('/login');
    }

    public function test_a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => null]);

        $this->post($thread->path() . '/replies' . $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    public function test_unauthorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();
        $reply = create('App\Reply');
        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('/login');

        $this->signIn()->delete("/replies/{$reply->id}")
            ->assertStatus(403);

    }

    public function test_authorized_users_can_delete_replies()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $this->delete("/replies/{$reply->id}");
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
         $this->assertEquals(0,$reply->thread->fresh()->replies_count);
    }

    public function test_authorized_users_can_update_replies()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $this->patch("/replies/{$reply->id}",
            ['body' => 'You have been changed']);
        $this->assertDatabaseHas('replies', ['id' => $reply->id,
            'body' => 'You have been changed']);

    }

    public function test_unauthorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling();
        $reply = create('App\Reply');
        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()->patch("/replies/{$reply->id}")
            ->assertStatus(403);

    }
    function test_replies_that_contain_spam_may_not_be_created(){
        $this->signIn();
        $thread=create('App\Thread');
        $reply = make('App\Reply',[
            'body'=>'Yahoo Customer Support'
        ]);

        $this->expectException(\Exception::class);

        $this->post($thread->path().'/replies',$reply->toArray())
        ->assertStatus(422);
    }

    function test_users_can_reply_a_maximum_of_once_per_minute(){
        $this->signIn();
        $thread=create('App\Thread');
        $reply = make('App\Reply',[
            'body'=>'my simple reply'
        ]);


        $this->post($thread->path().'/replies',$reply->toArray())
            ->assertStatus(200);

        $this->post($thread->path().'/replies',$reply->toArray())
            ->assertStatus(422);
    }

}
