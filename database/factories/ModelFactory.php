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
    $name = $faker->company;
    return [
        'name' => $name,
        'alias' => strtolower($name)
    ];
});

$factory->define(App\Entities\Contact::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\en_PH\PhoneNumber($faker));
    return [
        'mobile' => $faker->mobileNumber,
        'handle' => $faker->userName,
    ];
});

$factory->define(App\Entities\ShortMessage::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\en_PH\PhoneNumber($faker));
    return [
        'from' => $faker->mobileNumber,
        'to' => $faker->mobileNumber,
        'message' => $faker->sentence,
        'direction' => INCOMING
    ];
});

$factory->define(App\Entities\BlacklistedNumber::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\en_PH\PhoneNumber($faker));
    return [
        'mobile' => $faker->mobileNumber,
    ];
});

$factory->define(App\Entities\WhitelistedNumber::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\en_PH\PhoneNumber($faker));
    return [
        'mobile' => $faker->mobileNumber,
    ];
});

$factory->define(App\Entities\Info::class, function (Faker\Generator $faker) {
    return [
        'code' => $faker->word,
        'description' => $faker->sentence
    ];
});

$factory->define(App\Entities\Subscription::class, function (Faker\Generator $faker) {
    return [
        'code' => $faker->word,
        'description' => $faker->sentence
    ];
});

$factory->define(App\Entities\Pending::class, function (Faker\Generator $faker) {
    return [
        'code' => $faker->word,
    ];
});

$factory->define(App\Entities\Broadcast::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\en_PH\PhoneNumber($faker));
    return [
        'pending_id' => function () {
            return factory(App\Entities\Pending::class)->create()->id;
        },
        'from' => $faker->mobileNumber,
        'to' => $faker->mobileNumber,
        'message' => $faker->sentence,
    ];
});