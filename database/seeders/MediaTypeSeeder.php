<?php

namespace Database\Seeders;

use App\Models\MediaType;
use Illuminate\Database\Seeder;

class MediaTypeSeeder extends Seeder
{
    public function run(): void
    {
        MediaType::factory()
            ->count(4)
            ->sequence(
                ['name' => 'book'],
                ['name' => 'record'],
                ['name' => 'movie'],
                ['name' => 'game']
            )->create();
    }
}
