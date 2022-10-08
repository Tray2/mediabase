<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Publisher;
use App\Models\Series;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    protected int $mediaTypeId;

    protected function getMediaTypeId(): void
    {
        $this->mediaTypeId = MediaType::query()
            ->where('name', 'book')
            ->value('id');
    }

    public function definition(): array
    {
        $this->getMediaTypeId();

        return [
            'title' => $this->faker->words(2, true),
            'published_year' => $this->faker->year('now'),
            'isbn' => $this->faker->isbn13(),
            'blurb' => $this->faker->paragraph(),
            'series_id' => Series::factory()->create()->id,
            'genre_id' => Genre::factory()->create([
                'media_type_id' => $this->mediaTypeId,
            ])->id,
            'format_id' => Format::factory()->create([
                'media_type_id' => $this->mediaTypeId,
            ])->id,
            'publisher_id' => Publisher::factory()->create()->id,
        ];
    }
}
