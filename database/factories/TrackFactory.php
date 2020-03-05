<?php

/** @var Factory $factory */

use App\Model;
use App\Track;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Track::class, function (Faker $faker) {
    return [
        'track_no' => '01',
        'title' => 'Some Fake Title',
        'mix' => 'Some Fake Mix',
        'record_id' => 1
    ];
});
