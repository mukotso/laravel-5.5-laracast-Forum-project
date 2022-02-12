<?php

use Faker\Generator as Faker;



$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Thread::class, function ($faker){
    return [
        'user_id' => function(){
            return  factory('App\User')->create()->id;
        },
        'title' => $this->faker->sentence,
        'body' => $this->faker->sentence,
    ];
});


$factory->define(App\Reply::class, function ($faker){
    return [
        'thread_id' => function(){
            return  factory('App\Thread')->create()->id;
        },
        'user_id' => function(){
            return  factory('App\User')->create()->id;
        },
        'body' => $this->faker->sentence,
    ];
});
