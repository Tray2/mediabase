<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Genre;

class GenreTest extends TestCase
{
    use RefreshDatabase;


    /**
    * @test
    */
    public function the_genre_must_start_every_word_with_an_upper_case_letter()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $genre = factory(Genre::class)->make([
            'genre' => 'fanTasy',
            'type' => 'book'
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
            'type' => 'book'
        ]);

        factory(Genre::class)->create([
            'genre' => 'Fantasy',
            'type' => 'book'
        ]);

        factory(Genre::class)->create([
            'genre' => 'Crime',
            'type' => 'book'
        ]);

        $response = $this->get('/genres');

        $response->assertSeeInOrder(['Crime', 'Fantasy', 'Fiction']);
    }
}
