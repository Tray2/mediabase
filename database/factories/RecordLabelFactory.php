<?php

namespace Database\Factories;

use App\Models\RecordLabel;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecordLabelFactory extends Factory
{
    protected $model = RecordLabel::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
        ];
    }
}
