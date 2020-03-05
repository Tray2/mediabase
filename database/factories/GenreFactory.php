<?php


use App\Genre;
use Faker\Generator as Faker;

$factory->define(Genre::class, function (Faker $faker) {
    return [
        'genre' => $faker->word(2),
        'type' => $faker->word(1)
    ];
});
