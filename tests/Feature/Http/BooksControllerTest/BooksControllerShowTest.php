<?php

namespace Tests\Feature\Http\BooksControllerTest;

use App\Author;
use App\AuthorBook;
use App\Book;
use App\Format;
use App\Genre;
use App\Score;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BooksControllerShowTest extends BooksControllerTestHelper
{
    use RefreshDatabase;
    /**
     *  @test
     */
    public function guests_can_view_a_book_listing()
    {
        $author = factory(Author::class)->create([
            'first_name' => 'Robert',
            'last_name' => 'Jordan'
        ]);

        $format = factory(Format::class)->create([
            'format' => 'Paperback'
        ]);

        $genre = factory(Genre::class)->create([
            'genre' => 'Fantasy'
        ]);

        $book = factory(Book::class)->create([
            'title' => 'The Eye Of The World',
            'series' => 'The Wheel Of Time',
            'part' => '1',
            'format_id' => $format->id,
            'genre_id' => $genre->id,
            'isbn' => '0812511816',
            'released' => 1990,
            'reprinted' => 1990,
            'pages' => 782,
            'blurb' => 'The Wheel of Time turns and Ages come and go...'
        ]);

        factory(AuthorBook::class)->create([
            'book_id' => $book->id,
            'author_id' => $author->id
        ]);

        factory(Score::class)->create([
            'book_id' => $book->id,
            'score' => 4
        ]);

        $response = $this->get('/books/' . $book->id);

        $response->assertSee('Jordan, Robert');
        $response->assertSee('The Eye Of The World');
        $response->assertSee('The Wheel Of Time');
        $response->assertSee('Part 1');
        $response->assertSee('Released: 1990');
        $response->assertSee('isbn: 0812511816');
        $response->assertSee('Pages: 782');
        $response->assertSee('Genre: Fantasy');
        $response->assertSee('Score: 4');
        $response->assertSee('Format: Paperback');
        $response->assertSee('The Wheel of Time turns and Ages come and go...');
    }

    /**
     * @test
     */
    public function guests_can_visit_the_books_page_and_see_all_the_books_in_the_series()
    {
        $this->createForeignKeys();
        factory(Book::class)->create([
            'title' => 'The Eye Of The World',
            'series' => 'The Wheel Of Time',
            'part' => '1'
        ]);
        factory(Book::class)->create([
            'title' => 'The Great Hunt',
            'series' => 'The Wheel Of Time',
            'part' => '2'
        ]);
        factory(Book::class)->create([
            'title' => 'The Dragon Reborn',
            'series' => 'The Wheel Of Time',
            'part' => '3'
        ]);

        factory(AuthorBook::class)->create([
            'book_id' => 1,
            'author_id' => $this->author[0]->id
        ]);

        factory(AuthorBook::class)->create([
            'book_id' => 2,
            'author_id' => $this->author[0]->id
        ]);

        factory(AuthorBook::class)->create([
            'book_id' => 3,
            'author_id' => $this->author[0]->id
        ]);

        $response = $this->get('/books/1');
        $response->assertSeeInOrder(['The Eye Of The World', 'The Great Hunt', 'The Dragon Reborn']);
    }

}
