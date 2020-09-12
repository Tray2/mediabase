<?php

namespace Database\Factories;

use App\Models\Record;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RecordFactory extends Factory
{
    protected $model = Record::class;

    public function definition()
    {
        return [
            'artist_id' => 1,
            'title' => 'Some Fake Title',
            'released' => 1991,
            'genre_id' => 1,
            'format_id' => 1,
            'release_code' => '123456',
            'barcode' => '1234567890'
        ];
    }
}
