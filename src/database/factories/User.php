<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\JumpGate\Users\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
