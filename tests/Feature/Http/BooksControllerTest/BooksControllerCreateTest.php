<?php

namespace Tests\Feature\Http\BooksControllerTest;

use App\Models\Author;
use App\Models\AuthorBook;
use App\Models\Book;
use App\Models\BookCollection;
use App\Models\BookRead;
use App\Models\Format;
use App\Models\Genre;

class BooksControllerCreateTest extends BooksControllerTestHelper
{
    /**
     * @test
     */
    public function users_can_visit_the_create_books_page()
    {
        $this->signIn();

        $this->createForeignKeys();

        $response = $this->get('/books/create?author_id=1');

        $response->assertSee('name="title"', false);
        $response->assertSee('name="series"', false);
        $response->assertSee('name="part"', false);
        $response->assertSee('name="isbn"', false);
        $response->assertSee('name="released"', false);
        $response->assertSee('name="reprinted"', false);
        $response->assertSee('name="pages"', false);
        $response->assertSee('name="blurb"', false);
        $response->assertSee('name="_token"', false);

        $response->assertSee('input type="submit" value="Save"', false);

        $response->assertSee('name="genre_id"', false);
        $response->assertSee('name="format_id"', false);
        $response->assertSee('<option value="" disabled selected>Select your genre</option>', false);
        $response->assertSee('<option value="" disabled selected>Select your format</option>', false);
        $response->assertSee('name="read"', false);
    }

    /**
     * @test
     */
    public function the_create_books_page_contains_all_genres()
    {
        $this->signIn();
        $author = Author::factory()->create();
        $bookGenre = Genre::factory()->create([
            'genre' => 'Fantasy',
            'media_type_id' => env('BOOKS')
        ]);

        $otherGenre = Genre::factory()->create([
            'genre' => 'Rpg',
            'media_type_id' => env('GAMES')
        ]);

        $response = $this->get('books/create?author_id=' . $author->id);
        $response->assertSee($bookGenre->genre, false);
        $response->assertDontSee($otherGenre->genre, false);
    }

    /**
     * @test
     */
    public function the_create_books_page_does_only_contain_book_genres()
    {
        Author::factory()->create();
        $this->signIn();
        $genreNotToSee = Genre::factory()->create([
            'genre' => 'Rap',
            'media_type_id' => env('RECORDS')
        ]);
        $genreToSee = Genre::factory()->create([
            'genre' => 'Fantasy',
            'media_type_id' => env('BOOKS')
        ]);

        $response = $this->get('/books/create?author_id=1&type=BOOKS');

        $response->assertSee($genreToSee->genre);
        $response->assertDontSee($genreNotToSee->genre);
    }

    /**
     * @test
     */
    public function the_create_books_page_contains_all_formats()
    {
        Author::factory()->create();
        $this->signIn();
        $format1 = Format::factory()->create(['media_type_id' => env('BOOKS')]);
        $format2 = Format::factory()->create(['media_type_id' => env('BOOKS')]);
        $format3 = Format::factory()->create(['media_type_id' => env('BOOKS')]);
        $format4 = Format::factory()->create(['media_type_id' => env('BOOKS')]);

        $response = $this->get('/books/create?author_id=1&type=BOOKS');
        $response->assertSee($format1->format);
        $response->assertSee($format2->format);
        $response->assertSee($format3->format);
        $response->assertSee($format4->format);
    }

    /**
     * @test
     */
    public function the_create_books_page_does_only_contain_book_formats()
    {
        Author::factory()->create();
        $this->signIn();
        $formatNotToSee = Format::factory()->create([
            'format' => 'Lp',
            'media_type_id' => env('RECORDS')
        ]);
        $formatToSee = Format::factory()->create([
            'format' => 'Paperback',
            'media_type_id' => env('BOOKS')
        ]);

        $response = $this->get('/books/create?author_id=1');

        $response->assertSee($formatToSee->format);
        $response->assertDontSee($formatNotToSee->format);
    }

    /**
     * @test
     */
    public function users_trying_to_access_book_create_without_author_id_are_redirected_to_the_author_index_and_message_is_shown()
    {
        $this->signIn();
        $response = $this->get('/books/create');
        $response->assertLocation('/authors');
        $response = $this->get('/authors');
        $response->assertSee('You must specify an author.', false);
    }

    /**
     * @test
     */
    public function when_visiting_create_book_a_list_of_additional_authors_is_loaded()
    {
        $this->signIn();

        $this->createForeignKeys(5);

        $response = $this->get('books/create?author_id=1');

        $response->assertSee(e($this->author[1]->name), false);
        $response->assertSee(e($this->author[2]->name), false);
        $response->assertSee(e($this->author[3]->name), false);
        $response->assertSee(e($this->author[4]->name), false);
    }

    /**
     * @test
     */
    public function when_users_create_books_they_are_redirected_to_the_book_index_and_are_shown_a_success_message()
    {
        $this->createForeignKeys();
        $this->signIn();
        $book = Book::factory()->make();
        $book->author_id = $this->author[0]->id;

        $response = $this->post('/books', $book->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/books');

        $response = $this->get('/books');
        $response->assertSee(e($book->title) . ' successfully added.');
    }


    /**
     * @test
     */
    public function books_create_populates_the_author_id_field()
    {
        $this->createForeignKeys();
        Book::factory()->create();
        $this->signIn();
        $response = $this->get('/books/create?author_id=1');
        $response->assertSee('name="author_id" value="1"', false);
    }

    /**
     * @test
     */
    public function creating_a_book_adds_it_to_the_collection()
    {
        $this->createForeignKeys();
        $this->signIn();
        $book = Book::factory()->make();
        $book->author_id = $this->author[0]->id;

        $this->post('/books', $book->toArray());

        $this->assertEquals(1, BookCollection::count());
    }

    /**
     * @test
     */
    public function when_creating_a_book_additional_authors_can_be_added()
    {
        $this->createForeignKeys(3);
        $this->signIn();
        $book = Book::factory()->make();
        $book->author_id = $this->author[0]->id;
        $book->additional_authors = [2, 3];
        $this->post('/books', $book->toArray());

        $this->assertEquals(3, AuthorBook::count());
    }

    /**
     * @test
     */
    public function when_creating_a_book_it_can_be_marked_as_read()
    {
        $this->createForeignKeys(3);
        $this->signIn();
        $book = Book::factory()->make();
        $book->author_id = $this->author[0]->id;
        $book->read =  1;
        $this->post('/books', $book->toArray());
        $this->assertEquals(1, BookRead::count());
    }

    /**
     * @test
     */
    public function additional_authors_are_ordered_by_their_last_name_and_their_first_name()
    {
        $this->signIn();
        Author::create([
            'first_name' => 'Robert',
            'last_name' => 'Jordan',
            'slug' => 'jordan-robert'
        ]);

        Author::create([
            'first_name' => 'Sarah',
            'last_name' => 'Ash',
            'slug' => 'ash-sarah'
        ]);

        Author::create([
            'first_name' => 'Elizabeth',
            'last_name' => 'Moon',
            'slug' => 'moon-elizabeth'
        ]);

        Author::create([
            'first_name' => 'Patricia',
            'last_name' => 'Briggs',
            'slug' => 'briggs-patricia'
        ]);

        $response = $this->get('books/create?author_id=1');

        $response->assertSeeInOrder([
            'Ash, Sarah',
            'Briggs, Patricia',
            'Moon, Elizabeth'
        ]);
    }
}
