<?php

namespace Database\Factories;

use App\Models\Format;
use App\Models\Game;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Platform;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    protected $model = Game::class;

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
            'title' => $this->faker->word(),
            'release_year' => 1991,
            'genre_id' => Genre::factory()->create([
                'media_type_id' => $this->mediaTypeId,
            ])->id,
            'format_id' => Format::factory()->create([
                'media_type_id' => $this->mediaTypeId,
            ])->id,
            'platform_id' => Platform::factory()->create(),
            'blurb' => $this->faker->word(),
        ];
    }
}
