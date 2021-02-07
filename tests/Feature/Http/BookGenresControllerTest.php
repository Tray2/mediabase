<?php

namespace Tests\Feature\Http;

use App\Models\Author;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookGenresControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function when_visiting_the_index_page_the_amount_of_books_in_the_genre_is_shown()
    {
        Author::factory()->create();
        Format::factory()->create();
        $genre = Genre::factory()->create(['media_type_id' => env('BOOKS')]);
        Book::factory()->create();
        $response = $this->get('/books/genres');
        $response->assertSeeTextInOrder([$genre->genre, '1'], false);
    }
}
