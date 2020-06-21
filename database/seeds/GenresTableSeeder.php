<?php

use App\Genre;
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
            'type' => 'books'
        ],
        [
            'genre' => 'Sci Fi',
            'type' => 'books'
        ],
        [
            'genre' => 'Crime',
            'type' => 'books'
        ],
        [
            'genre' => 'Medical Thriller',
            'type' => 'books'
        ],
        [
            'genre' => 'Hip Hop',
            'type' => 'records'
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
            Genre::create($genre);
        }
    }
}
