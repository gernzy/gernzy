<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Gernzy\Server\Models\Tag;
use Illuminate\Support\Str;
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

$factory->define(Tag::class, function (Faker $faker) {
    $rand = rand(0,10);
    return [
        'name' => $faker->word()
    ];
});
