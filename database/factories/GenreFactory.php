<?php
namespace Database\Factories;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GenreFactory extends Factory
{
    protected $model = Genre::class;

    public function definition()
    {
        return [
            'genre' => $this->faker->word(2),
            'media_type_id' => env('BOOKS')
        ];
    }
}
