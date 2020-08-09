<?php


use App\Genre;
use Faker\Generator as Faker;

$factory->define(Genre::class, function (Faker $faker) {
    return [
        'genre' => $faker->word(2),
        'media_type_id' => env('BOOKS')
    ];
});
