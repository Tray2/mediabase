<?php

namespace Database\Factories;

use App\Models\MediaType;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaTypeFactory extends Factory
{
    protected $model = MediaType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
