<?php

// database/factories/ModelFactory.php

$factory->define(App\Post::class, function (Faker\Generator $faker) {
    // Get a random user
    $user = \App\User::inRandomOrder()->first();

    // generate fake data for post
    return [
        'user_id' => $user->id,
        'title' => $faker->sentence,
        'body' => $faker->text,
    ];
});