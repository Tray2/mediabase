<?php

use Faker\Generator as Faker;

$factory->define(App\Author::class, function (Faker $faker) {
    $firstName = $faker->firstName();
    $lastName = $faker->lastName();
    return [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'slug' => Str::slug($lastName . ', ' . $firstName)
    ];
});
