<?php

namespace Database\Factories;

use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovieFactory extends Factory
{
    protected $model = Movie::class;

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
            'title' => $this->faker->words(3, true),
            'release_year' => $this->faker->year(),
            'genre_id' => Genre::factory()->create([
                'media_type_id' => $this->mediaTypeId,
            ])->id,
            'format_id' => Format::factory()->create([
                'media_type_id' => $this->mediaTypeId,
            ])->id,
            'runtime' => 90,
            'blurb' => $this->faker->sentence,
        ];
    }
}
