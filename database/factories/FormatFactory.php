<?php

namespace Database\Factories;

use App\Models\Format;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FormatFactory extends Factory
{
    protected $model = Format::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
