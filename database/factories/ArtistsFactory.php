<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Artist::class, function (Faker $faker) {
    $name = $faker->name();
    return [
        'name' => $name,
        'slug' => Str::slug($name)
    ];
});
