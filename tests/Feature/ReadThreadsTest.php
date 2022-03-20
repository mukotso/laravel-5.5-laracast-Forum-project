<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
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

        $response = $this->get('/threads/' . $this->thread->id);
        $response->assertSee($this->thread->title);

    }



    function test_a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');

        $this->get('/threads' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel);

    }

    public function test_a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'johnDoe']));

        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByJohn = create('App\Thread');

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }

    public function test_a_user_can_filter_by_popularity()
    {
        $threadTwoReplies = create('App\Models\Thread');
        create('App\Models\Reply', ['thread_id' => $threadTwoReplies->id], 2);

        $threadThreeReplies = create('App\Models\Thread');
        create('App\Models\Reply', ['thread_id' => $threadThreeReplies->id], 3);

        $threadZeroReplies = $this->thread;

        $response = $this->get('threads?popular');
        $threadsFromResponse = $response->baseResponse->original->getData()['threads'];
        $this->assertEquals([3, 2, 0], $threadsFromResponse->pluck('replies_count')->toArray());
    }

    public function test_a_user_can_filter_threads_by_those_that_are_unanswered(){
        $thread=create('App\Thread');
        create('App\Reply',['thread_id'=>$thread->id]);

        $response=$this->getJson('threads?unanswered=1')->json();
        $this->assertCount(1,$response);
    }

    public function test_a_user_all_replies_for_a_given_thread()
    {
        $thread = create('App\Thread');
        create('App\Thread', ['thread_id'],2);
        $response=$this->getJson($thread->path().'replies')->json();
        $this->assertCount(1,$response['data']);
        $this->asssertEquals(2,$response['total']);
    }


}