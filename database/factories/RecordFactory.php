<?php

namespace Database\Factories;

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

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'released' => $this->faker->year('now'),
            'genre_id' => Genre::factory()->create([
                'media_type_id' => MediaType::query()
                    ->where('name', 'record')
                    ->value('id'),
            ]),
            'format_id' => Format::factory()->create([
                'media_type_id' => MediaType::query()
                    ->where('name', 'record')
                    ->value('id'),
            ]),
            'record_label_id' => RecordLabel::factory()->create(),
            'country_id' => Country::factory()->create(),
            'barcode' => $this->faker->creditCardNumber(),
            'spine_code' => $this->faker->creditCardNumber(),
        ];
    }
}
