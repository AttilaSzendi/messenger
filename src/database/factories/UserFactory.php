<?php

use Faker\Generator as Faker;

/**
 * @var $factory Illuminate\Database\Eloquent\Factory
 */
$factory->define(\Stilldesign\Messenger\Models\User::class, function (Faker $faker) {
    static $password;

    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
