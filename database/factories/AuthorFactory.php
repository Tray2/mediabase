<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition(): array
    {
        return [
            'first_name' => Str::replace("'", '', $this->faker->firstName()),
            'last_name' => Str::replace("'", '', $this->faker->lastName()),
        ];
    }
}
