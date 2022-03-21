<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class mentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    public function mentioned_users_in_a_reply_are_notified()
    {
        $john = create('App\User', ['name' => JohnDoe]);

        $this->signIn($john);

        $jane = create('App\User', ['name' => JaneDoe]);
        $thread = create('App\Thread');
        $reply = make('App\Reply', [
            'body' => '@JaneDoe look at this !'
        ]);

        $this->json('post', $thread->path() . '/replies' . $reply->toArray());
        $this->assertCount(1, $jane->notifications);
    }

    public function test_it_can_fetch_all_mentioned_users_starting_with_a_given_character()
    {
        create('App\user', ['name' => 'johnDoe']);
        create('App\user', ['name' => 'johndoe2']);
        create('App\user', ['name' => 'janeDoe']);
       $results= $this->json('GET', 'api/users', ['name' => 'john']);

        $this->assertCount(2,$results->json());

    }

}
