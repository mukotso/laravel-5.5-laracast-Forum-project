<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $this->signIn();
        $thread = create('App\thread')->subscribe();
        $this->assertCount(0, auth()->user()->notifications);
        $thread->addReply([
            'user_id' => auth()->id,
            'body' => 'some reply here'
        ]);
        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create('app\User')->id,
            'body' => 'some reply here'
        ]);
        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    function a_user_can_fetch_their_unread_notifications()
    {
        $this->signIn();
        $thread = create('App\thread')->subscribe();
        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'some reply here'
        ]);

        $response = $this->getJson('/profiles/' . auth()->user()->name . '/notifications')->json();
        $this->assertCount(1, $response);
    }

    function a_user_can_mark_a_notification_as_read()
    {
        $this->signIn();
        $thread = create('App\thread')->subscribe();
        $thread->addReply([
            'user_id' => auth()->id,
            'body' => 'some reply here'
        ]);
        $user = auth()->user();
        $this->assertCount(1, $user->unreadNotifications);

        $notificationId = auth()->user()->unreadNotifications->first()->id;
        $this->delete('/profiles/' . auth()->user()->name . '/notifications/{$notificationId}');

        $this->assertCount(0, auth()->user()->fresh()->unreadNotifications);
    }
}
