<?php

use Faker\Generator as Faker;

$factory->define(App\Book::class, function (Faker $faker) {
    return [
        'format_id' => 1,
        'genre_id' => 1,
        'title' => $faker->word(2),
        'series' => 'Some Fake Serie',
        'part' => 9999,
        'isbn' => '0123456789',
        'released' => 1900,
        'reprinted' => 2012,
        'pages' => 100,
        'blurb' => 'Some nice fake text about the book'
    ];
});
