<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Message;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'created_at' => date('Y-m-d H:i'),
        'message' => $faker->text(50),
        'receiver_id' => 2,
    ];
});
