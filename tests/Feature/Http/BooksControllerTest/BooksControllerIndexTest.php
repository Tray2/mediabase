<?php

namespace Tests\Feature\Http\BooksControllerTest;

use App\Models\Author;
use App\Models\AuthorBook;
use App\Models\Book;
use App\Models\BookCollection;
use App\Models\BookRead;
use App\Models\MediaType;
use App\Models\Score;

class BooksControllerIndexTest extends BooksControllerTestHelper
{
    /**
     * @test
     */
    public function guests_can_list_all_books()
    {
        $this->createForeignKeys();

        $author1 = Author::factory()->create();

        $author2 = Author::factory()->create();


        $book1 = Book::factory()->create([
            'genre_id' => $this->genre[0]->id,
            'format_id' => $this->format[0]->id
        ]);

        $book2 = Book::factory()->create([
            'genre_id' => $this->genre[0]->id,
            'format_id' => $this->format[0]->id
        ]);

        AuthorBook::factory()->create([
            'book_id' => $book1->id,
            'author_id' => $author1->id
        ]);

        AuthorBook::factory()->create([
            'book_id' => $book2->id,
            'author_id' => $author2->id
        ]);

        $response = $this->get('/books');

        $response->assertSee($author1->name);
        $response->assertSee($book1->title);
        $response->assertSee($book1->series);
        $response->assertSee($book1->part);
        $response->assertSee($book1->released);
        $response->assertSee($book1->genre->genre);
        $response->assertSee($book1->format->format);

        $response->assertSee($author2->name);
        $response->assertSee($book2->title);
        $response->assertSee($book2->series);
        $response->assertSee($book2->part);
        $response->assertSee($book2->released);
        $response->assertSee($book2->genre->genre);
        $response->assertSee($book2->format->format);
    }

    /**
     * @test
     */
    public function if_no_books_exists_then_show_no_books_found_is_shown_in_books_index_view()
    {
        $response = $this->get('/books');
        $response->assertStatus(200);
        $response->assertSee('No books found');
    }

    /**
     * @test
     */
    public function only_users_can_see_the_add_book_button()
    {
        $guestResponse = $this->get('/books');
        $this->signIn();
        $userResponse = $this->get('/books');

        $guestResponse->assertDontSee('Add book');
        $userResponse->assertSee('Add book');
    }

    /**
     * @test
     */
    public function the_add_book_button_on_book_index_links_to_the_author_index()
    {
        $this->signIn();
        $response = $this->get('/books');
        $response->assertSee('href="/authors"', false);
    }

    /**
     * @test
     */
    public function book_rating_is_shown_in_book_index_view()
    {
        $this->createForeignKeys();
        $book = Book::factory()->create();

        Score::factory()->create([
            'item_id' => $book->id,
            'score' => '3',
            'media_type_id' => MediaType::where('media', 'Books')->pluck('id')->first()
        ]);

        $response = $this->get('/books');


        $response->assertSee('3.0/5.0', false);
    }

    /**
     * @test
     */
    public function books_without_rating_shows_not_rated()
    {
        $this->createForeignKeys();
        Book::factory()->create();

        $response = $this->get('/books');

        $response->assertSee('Not rated', false);
    }

    /**
     * @test
     */
    public function collected_books_are_shown_as_collected()
    {
        $this->createForeignKeys();
        $this->signIn();
        Book::factory()->create();
        BookCollection::factory()->create(['user_id' => 1, 'book_id' => 1]);
        $response = $this->get('/books');
        $response->assertSee('Collected', false);
    }


    /**
     * @test
     */
    public function read_books_are_shown_as_read()
    {
        $this->createForeignKeys();
        $this->signIn();
        Book::factory()->create();
        BookRead::factory()->create(['user_id' => 1, 'book_id' => 1]);
        $response = $this->get('/books');
        $response->assertSee('Read', false);
    }

}
