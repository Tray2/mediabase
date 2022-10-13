<?php

namespace Database\Factories;

use App\Models\Track;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrackFactory extends Factory
{
    protected $model = Track::class;

    public function definition(): array
    {
        return [
            'position' => '0' . $this->faker->randomNumber(1, 9),
            'title' => $this->faker->jobTitle(),
            'duration' => $this->faker->time('i:s'),
            'mix' => ''
        ];
    }
}
