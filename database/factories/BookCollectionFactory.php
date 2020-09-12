<?php

namespace Database\Factories;

use App\Models\BookCollection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookCollectionFactory extends Factory
{
    protected $model = BookCollection::class;

    public function definition()
    {
        return [
          'book_id' => 1,
          'user_id' => 1
        ];
    }
}
