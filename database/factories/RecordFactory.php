<?php

namespace Database\Factories;

use App\Models\Format;
use App\Models\Genre;
use App\Models\Record;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecordFactory extends Factory
{
    protected $model = Record::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'released' => $this->faker->year('now'),
            'genre_id' => Genre::factory()->create(),
            'format_id' => Format::factory()->create(),
            'barcode' => $this->faker->creditCardNumber(),
            'spine_code' => $this->faker->creditCardNumber(),
        ];
    }
}
