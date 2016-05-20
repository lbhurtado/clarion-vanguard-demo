<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
use App\Mobile;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Entities\Group::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company
    ];
});

$factory->define(App\Entities\Contact::class, function (Faker\Generator $faker) {
    return [
        'mobile' => Mobile::number($faker->numberBetween(900,999) . $faker->numberBetween(1000000,9999999)),
        'handle' => $faker->userName,
    ];
});

$factory->define(App\Entities\ShortMessage::class, function (Faker\Generator $faker) {
    return [
        'from' => Mobile::number($faker->numberBetween(900,999) . $faker->numberBetween(1000000,9999999)),
        'to' => Mobile::number($faker->numberBetween(900,999) . $faker->numberBetween(1000000,9999999)),
        'message' => $faker->sentence,
    ];
});

$factory->define(App\Entities\BlacklistedNumber::class, function (Faker\Generator $faker) {
    return [
        'mobile' => Mobile::number($faker->numberBetween(900,999) . $faker->numberBetween(1000000,9999999)),
    ];
});

$factory->define(App\Entities\WhitelistedNumber::class, function (Faker\Generator $faker) {
    return [
        'mobile' => Mobile::number($faker->numberBetween(900,999) . $faker->numberBetween(1000000,9999999)),
    ];
});
