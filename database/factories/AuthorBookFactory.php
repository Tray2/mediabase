<?php
namespace Database\Factories;

use App\Models\AuthorBook;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorBookFactory extends Factory
{
    protected $model = AuthorBook::class;

    public function definition()
    {
        return [
            'author_id' => 1,
            'book_id' => 1
        ];
    }
}
