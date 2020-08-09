<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Genre;

class GenreTest extends TestCase
{
    /**
    * @test
    */
    public function the_genre_must_start_every_word_with_an_upper_case_letter()
    {
        $this->signIn();

        $genre = factory(Genre::class)->make([
            'genre' => 'fanTasy',
            'media_type_id' => env('BOOKS')
        ]);

        $this->post('/genres', $genre->toArray());
        $this->assertEquals(1, Genre::where('genre', 'Fantasy')->count());
    }

    /**
    * @test
    */
    public function when_listing_genres_they_are_sorted_alphabetically()
    {
        factory(Genre::class)->create([
            'genre' => 'Fiction',
            'media_type_id' => env('BOOKS')
        ]);

        factory(Genre::class)->create([
            'genre' => 'Fantasy',
            'media_type_id' => env('BOOKS')
        ]);

        factory(Genre::class)->create([
            'genre' => 'Crime',
            'media_type_id' => env('BOOKS')
        ]);

        $response = $this->get('/genres');

        $response->assertSeeInOrder(['Crime', 'Fantasy', 'Fiction']);
    }
}
