<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\youWereMentioned;
use App\User;

class NotifyMentionedUsers
{
    public function __construct()
    {
        //
    }

    public function handle(ThreadReceivedNewReply $event)
    {
        User::whereIn('name',$event->reply->mentionedUsers())->get()
            ->each(function ($user) use ($event) {
                $user->notify(new youWereMentioned($event->reply));
            });
    }
}
