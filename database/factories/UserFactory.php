<?php

use App\Province;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    $province = Province::all()->random();

    return [
        'name' => $faker->name,
        'postal_code' => $faker->randomElement(['M5G 2G8', 'M5G2G8']),
        'salary' => $faker->randomFloat(2, 10000, 100000),
        'telephone' => (string)$faker->numberBetween(1111111111, mt_getrandmax()),
        'province_id' => $province->id,
    ];
});

