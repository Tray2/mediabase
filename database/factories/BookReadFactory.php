<?php

namespace Database\Factories;

use App\Models\BookRead;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookReadFactory extends Factory
{
    protected $model = BookRead::class;

    public function definition()
    {
        return [
            'book_id' => 1,
            'user_id' => 1
        ];
    }
}
