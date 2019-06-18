<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\UserPushToken;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(UserPushToken::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(1,10000),
        'push_token' => Str::random(10),
    ];
});
