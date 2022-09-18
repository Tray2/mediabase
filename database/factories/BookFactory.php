<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->words(2, true),
            'published_year' => $this->faker->year('now'),
            'series' => $this->faker->words(2, true),
            'genre_id' => Genre::factory()->create()->id,
            'format_id' => Format::factory()->create()->id,
        ];
    }
}
