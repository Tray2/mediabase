<?php

use App\Format;
use Faker\Generator as Faker;

$factory->define(Format::class, function (Faker $faker) {
    return [
        'format' => $faker->word(2),
        'media_type_id' => 1
    ];
});
