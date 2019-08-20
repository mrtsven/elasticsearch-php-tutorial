<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'age' => $faker->numberBetween(18, 45),
        'email' => $faker->email,
        'email_verified_at' => now(),
        'name' => $faker->name(),
        'password' => bcrypt('1234'),
    ];
});
