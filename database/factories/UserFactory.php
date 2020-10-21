<?php

/** @var Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Faker\Provider\ru_RU\Person as Person;
use Illuminate\Database\Eloquent\Factory;

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

$factory->define(User::class, function (Faker $faker) {
    $faker->addProvider(new Person($faker));
    return [
        'name' => $faker->firstName('male'),
        'surname' => $faker->lastName('male'),
        'father_name' => $faker->middleName('male'),
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('password'),
    ];
});
