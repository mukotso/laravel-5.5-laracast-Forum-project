<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp():void{
        parent::setUp();
        $this->thread=Thread::factory()->create();
    }

    public function test_a_user_can_view_all_threads()
    {
        //create a threat and check if its seen in the threads page

        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);
    }

//    public function test_a_user_can_view_single_thread()
//    {
//        //create a threat and check if its seen in the threads page
//
//        $response = $this->get('/threads/'.$this->thread->id);
//        $response->assertSee($this->thread->title);
//
//    }
//    public function test_a_user_can_read_replies_that_are_associated_to_a_thread(){
//        //given we have a thread
//
//        $reply=Reply::factory()->create(['thread_id'=>$this->thread->id]);
//        //that thread icludes replies
//        //when we visit our threads page
//        $response =$this->get('/threads/'.$this->thread->id);
//
//        //we should see replies when we visit that page
//        $response->assertSee($reply->body);
//    }
}
