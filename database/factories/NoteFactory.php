<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Note;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Note::class, function (Faker $faker) {
    return [
        'note' => Str::random(10),
    ];
});
