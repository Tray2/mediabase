<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'release_year' => 1991,
            'format_id' => 1,
            'genre_id' => 1,
            'platform_id' => 1,
            'blurb' => $this->faker->word(),
        ];
    }
}
