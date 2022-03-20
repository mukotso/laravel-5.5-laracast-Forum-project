<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;

class ThreadReceivedNewReply
{
    use Dispatchable, SerializesModels;

    public function __construct($reply)
    {
        $this->reply=$reply;
    }


}
