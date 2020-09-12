<?php
namespace Database\Factories;

use App\Models\Format;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FormatFactory extends Factory
{
    protected $model = Format::class;

    public function definition()
    {
        return [
            'format' => $this->faker->word(2),
            'media_type_id' => env('BOOKS')
        ];
    }
}
