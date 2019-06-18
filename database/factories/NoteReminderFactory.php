<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\NoteReminder;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(NoteReminder::class, function (Faker $faker) {
    return [
        'note_id' => $faker->numberBetween(1,10000),
        'utc_notification_time' => \Carbon\Carbon::now(),
        'local_notification_time' => \Carbon\Carbon::now()->timezone('Europe/Budapest'),
        'local_time_zone' => 'Europe/Budapest',
    ];
});
