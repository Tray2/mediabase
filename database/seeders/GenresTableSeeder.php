<?php
namespace Database\Seeders;

use App\Models\Genre;
use App\Models\MediaType;
use Illuminate\Database\Seeder;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $genres = [
        [
            'genre' => 'Fantasy',
            'media' => 'Books'
        ],
        [
            'genre' => 'Sci Fi',
            'media' => 'Books'
        ],
        [
            'genre' => 'Crime',
            'media' => 'Books'
        ],
        [
            'genre' => 'Medical Thriller',
            'media' => 'Books'
        ],
        [
            'genre' => 'Hip Hop',
            'media' => 'Records'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->genres as $genre) {
            $genre['media_type_id'] = MediaType::where('media', $genre['media'])->pluck('id')->first();
            unset($genre['media']);
            Genre::create($genre);
        }
    }
}
