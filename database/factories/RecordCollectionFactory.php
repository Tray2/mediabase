<?php

namespace Database\Factories;

use App\Models\RecordCollection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RecordCollectionFactory extends Factory
{
    protected $model = RecordCollection::class;

    public function definition()
    {
        return [
            'record_id' => 1,
            'user_id' => 1
        ];
    }
}
