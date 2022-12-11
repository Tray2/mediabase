<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovieFactory extends Factory
{
    protected $model = Movie::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'release_year' => $this->faker->year(),
            'format_id' => 1,
            'genre_id' => 1,
            'length' => '1h 50m'

        ];
    }
}
