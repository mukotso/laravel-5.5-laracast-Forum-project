<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Tests\TestCase;

class UserTest extends TestCase
{
public function test_a_user_can_fetch_their_most_recent_replies(){
    $user =create('App\User');
    $reply =create('App\Reply',['user_id'=>$user->id]);

    $this->assertEquals($reply->id, $user->lasteply->id);
}

}
