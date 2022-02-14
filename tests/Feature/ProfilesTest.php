<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfilesTest extends TestCase
{

    public function a_user_has_profile ()
    {
        $user = create('App\ User');

        $this->get('/profiles/'.$user->name)
            ->assertSee($user->name);
    }


    public function profile_has_user_threads ()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->get('/profiles/'.auth()->user()->name)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
