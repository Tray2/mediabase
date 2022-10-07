<?php

namespace Database\Factories;

use App\Models\Artist;
use App\Models\Country;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Record;
use App\Models\RecordLabel;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecordFactory extends Factory
{
    protected $model = Record::class;

    protected int $mediaTypeId;

    protected function getMediaTypeId(): void
    {
        $this->mediaTypeId = MediaType::query()
            ->where('name', 'record')
            ->value('id');
    }


    public function definition(): array
    {
        $this->getMediaTypeId();
        return [
            'title' => $this->faker->word(),
            'release_year' => $this->faker->year('now'),
            'genre_id' => Genre::factory()->create([
                'media_type_id' => $this->mediaTypeId,
            ]),
            'format_id' => Format::factory()->create([
                'media_type_id' => $this->mediaTypeId,
            ]),
            'record_label_id' => RecordLabel::factory()->create(),
            'country_id' => Country::factory()->create(),
            'barcode' => $this->faker->creditCardNumber(),
            'spine_code' => $this->faker->creditCardNumber(),
            'artist_id' => Artist::factory()->create(),
        ];
    }
}
