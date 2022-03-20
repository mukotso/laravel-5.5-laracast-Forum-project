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
        collect($event->reply->mentionedUsers())
            ->map(function ($name) {
                return User::where('name', $name)->first();
            })
            ->filter()
            ->each(function ($user) use ($event) {
                $user->notify(new youWereMentioned($event->reply));
            });

    }
}
