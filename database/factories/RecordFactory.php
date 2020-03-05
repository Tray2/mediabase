<?php

/** @var Factory $factory */

use App\Model;
use App\Record;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Record::class, function (Faker $faker) {
    return [
        'artist_id' => 1,
        'title' => 'Some Fake Title',
        'released' => 1991,
        'genre_id' => 1,
        'format_id' => 1,
        'release_code' => '123456',
        'barcode' => '1234567890'
    ];
});
