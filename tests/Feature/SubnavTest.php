<?php

namespace Tests\Feature;

use Tests\TestCase;

class SubnavTest extends TestCase
{
    /**
    * @test
    */
    public function when_visiting_the_books_index_authors_genres_and_formats_are_shown()
    {
        $response = $this->get('/books');
        $response->assertSeeTextInOrder(['Authors', 'Formats', 'Genres'], false);
        $response->assertSee(['/authors', '/formats?type=BOOKS', '/genres?type=BOOKS'], false);
    }

    /**
     * @test
     */
    public function when_visiting_the_records_index_artists_genres_and_formats_are_shown()
    {
        $response = $this->get('/records');
        $response->assertSeeTextInOrder(['Artists', 'Formats', 'Genres'], false);
        $response->assertSee(['/artists', '/formats?type=RECORDS', '/genres?type=RECORDS'], false);
    }

    /**
     * @test
     */
    public function when_visiting_the_books_format_authors_genres_and_formats_are_shown()
    {
        $response = $this->get('/formats?type=BOOKS');
        $response->assertSeeTextInOrder(['Authors', 'Formats', 'Genres'], false);
        $response->assertSee(['/authors', '/formats?type=BOOKS', '/genres?type=BOOKS'], false);
    }

    /**
     * @test
     */
    public function when_visiting_the_records_formats_artists_genres_and_formats_are_shown()
    {
        $response = $this->get('/formats?type=RECORDS');
        $response->assertSeeTextInOrder(['Artists', 'Formats', 'Genres'], false);
        $response->assertSee(['/artists', '/formats?type=RECORDS', '/genres?type=RECORDS'], false);
    }
}
