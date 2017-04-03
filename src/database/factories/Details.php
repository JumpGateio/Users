<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\JumpGate\Users\Models\User\Detail::class, function (Faker\Generator $faker) {
    return [
        'timezone' => 'America/Chicago',
    ];
});
