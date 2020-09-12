<?php
namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition()
    {
        return [
            'format_id' => 1,
            'genre_id' => 1,
            'title' => $this->faker->word(2),
            'series' => 'Some Fake Serie',
            'part' => 9999,
            'isbn' => '0123456789',
            'released' => 1900,
            'reprinted' => 2012,
            'pages' => 100,
            'blurb' => 'Some nice fake text about the book'
        ];
    }
}
