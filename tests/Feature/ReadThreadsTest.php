<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(){
        parent::setUp();
        $this->thread=factory('App\Thread')->create();
    }

    public function test_a_user_can_view_all_threads()
    {
        //create a threat and check if its seen in the threads page

        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);
    }

    public function test_a_user_can_view_single_thread()
    {
        //create a threat and check if its seen in the threads page

        $response = $this->get('/threads/'.$this->thread->id);
        $response->assertSee($this->thread->title);

    }
    public function test_a_user_can_read_replies_that_are_associated_to_a_thread(){
        //given we have a thread

        $reply=factory('App\Reply')->create(['thread_id'=>$this->thread->id]);
        //that thread icludes replies
        //when we visit our threads page
        $response =$this->get($this->thread->path());

        //we should see replies when we visit that page
        $response->assertSee($reply->body);
    }

    function  a_user_can_filter_threads_according_to_a_channel(){
        $channel=create('App\Channel');
        $threadInChannel =create('App\thread', ['channel_id'=>$channel->id]);
        $threadNotInChannel =create('App\Thread');

        $this->get('/threads'. $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel);

}

public function a_user_can_filter_threads_by_any_username(){
        $this->signIn(create('App\User', ['name'=>'johnDoe']));

        $threadByJohn = create ('App\Thread',['user_id'=>auth()->id()]);
        $threadNotByJohn =create('App\Thread');

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn ->title)
            ->assertDontSee($threadNotByJohn->title);
}

    public function a_user_can_filter_by_popularity()
    {
        $threadTwoReplies = create('App\Models\Thread');
        create('App\Models\Reply', ['thread_id' => $threadTwoReplies->id], 2);

        $threadThreeReplies = create('App\Models\Thread');
        create('App\Models\Reply', ['thread_id' => $threadThreeReplies->id], 3);

        $threadZeroReplies = $this->thread;

        $response = $this->get('threads?popular');
        $threadsFromResponse = $response->baseResponse->original->getData()['threads'];
        $this->assertEquals([3,2,0], $threadsFromResponse->pluck('replies_count')->toArray());
    }
}