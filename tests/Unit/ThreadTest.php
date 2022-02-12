<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(){
        parent::setUp();
        $this->thread=create('App\Thread');
    }

    public function test_a_thread_has_replies()
    {

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection',
            $this->thread->replies);
    }

    public function test_a_thread_has_a_creator(){
        //given we have a thread

        //we should see replies when we visit that page
        $this->assertInstanceOf(User::class,$this->thread->creator);
    }
//
//    public function test_a_thread_can_add_a_reply(){
//        //given we have a thread
//
//        $this->thread->addReply([
//                "body"=>"foor",
//                'user_id'=>1
//            ]
//        );
//
//        $this->assertCount(1,$this->thread->replies)
//    }
//
//
//    public function test_a_thread_belongs_to_a_channnel(){
//
//
//        $thread=create(Thread);
//        $this->asssertInstanceOf(Channel::class ,$thread->channel)
//
//    }
//
//    public function test_a_thread_can_make_a_string_path(){
//        $thread=create  (User);
//
//        $this->assertEquals('/threads/{$thread->channel->slug}/{thread->id}',$thread->path()
//        );
//    }
}
