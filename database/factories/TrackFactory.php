<?php

namespace Database\Factories;

use App\Models\Track;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TrackFactory extends Factory
{
    protected $model = Track::class;

    public function definition()
    {
        return [
            'track_no' => '01',
            'title' => 'Some Fake Title',
            'mix' => 'Some Fake Mix',
            'record_id' => 1
        ];
    }
}
