<?php

namespace Tests\Unit;

use App\Channel;
use App\Notifications\ThreadWasUpdated;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\Notification;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->thread = create('App\Thread');
    }

    public function test_a_thread_has_replies()
    {

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection',
            $this->thread->replies);
    }

    public function test_a_thread_has_a_creator()
    {
        //given we have a thread

        //we should see replies when we visit that page
        $this->assertInstanceOf(User::class, $this->thread->creator);
    }

//
    public function test_a_thread_can_add_a_reply()
    {
        //given we have a thread

        $this->thread->addReply([
                "body" => "foor",
                'user_id' => 1
            ]
        );

        $this->assertCount(1, $this->thread->replies);
    }

    public function test_a_thread_belongs_to_a_channel()
    {


        $thread = create('App\Thread');
        $this->assertInstanceOf(Channel::class, $thread->channel);

    }

    public function test_a_thread_can_make_a_string_path()
    {
        $thread = create('App\Thread');

        $this->assertEquals('/threads/' . $thread->channel->slug . '/' . $thread->id, $thread->path()
        );
    }

    function a_thread_can_be_subscribed_to()
    {

        $thread=create('App\Thread');
//        $this->signIn();
        $thread->subscribe($userId=1);
        $this->assertCount(1,$thread->subscriptions()->where('user_id',$userId)->count());

    }

    function a_thread_can_be_unsubscribed_from(){
        $thread=create('App\Thread');
        $thread->subscribe($userId=1);
        $thread->unsubscribe($userId);
        $this->assertCount(0,$thread->subsscriptions);
    }
    function it_knows_if_the_authenticated_user_is_subscribed_to_it(){
         $thread= create('App\Thread');

         $this->signIn();
        $this->assertFalse($thread->isSubscribedTo);
          $thread->subscribe();
          $this->assertTrue($thread->isSubscribedTo);
    }

    function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added(){
        Notification::fake();
        $this->signIn();
        $this->thread->subscribe();
        $this->thread->addReply([
                "body" => "foobar",
                'user_id' => 1
            ]
        );
        Notification::assertSentTo(auth()->user(),ThreadWasUpdated::class);
    }
}
