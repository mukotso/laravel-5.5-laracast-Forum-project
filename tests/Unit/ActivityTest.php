<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;
    public function it_records_activity_when_a_thread_is_created()
    {
        $this->signIn();

        $thread = create('App\Models\Thread');

        $this->assertDatabaseHas('activities', [
            'type'          => 'thread_created',
            'user_id'       => auth()->id(),
            'subject_id'    => $thread->id,
            'subject_type'  => 'App\Models\Thread'
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    public function it_records_activity_when_a_reply_is_created()
    {
        $this->signIn();

        $thread = create('App\Models\Thread');
        $reply = create('App\Models\Reply');

        $this->assertDatabaseHas('activities', [
            'type'          => 'reply_created',
            'user_id'       => auth()->id(),
            'subject_id'    => $reply->id,
            'subject_type'  => 'App\Models\Reply'
        ]);

        $this->assertEquals(2, Activity::count());

        $activity = Activity::first();
        $this->assertEquals($activity->subject->id, $reply->id);
    }

    public function it_fetches_users_feed()
    {
        $this->signIn(); 

        create('App\Thread', ['user_id' => auth()->id()], 2);

        auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        $feed = Activity::feed(auth()->user());

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}
