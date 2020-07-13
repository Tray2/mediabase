<?php

namespace Tests\Feature\Http\BooksControllerTest;

use App\Author;
use App\AuthorBook;
use App\Book;
use App\BookCollection;
use App\BookRead;
use App\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BooksControllerCreateTest extends BooksControllerTestHelper
{
    use RefreshDatabase;

    /** @test */
    public function users_can_visit_books_create()
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
    public function when_visiting_books_create_a_list_of_all_book_genres_is_loaded()
    {
        $this->signIn();
        $author = factory(Author::class)->create();
        $bookGenre = factory(Genre::class)->create([
            'genre' => 'Fantasy',
            'type' => 'books'
        ]);

        $otherGenre = factory(Genre::class)->create([
            'genre' => 'Rpg',
            'type' => 'games'
        ]);

        $response = $this->get('books/create?author_id=' . $author->id);
        $response->assertSee($bookGenre->genre, false);
        $response->assertDontSee($otherGenre->genre, false);
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
        $book = factory(Book::class)->make();
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
    public function books_create_populates_the_author_id_field()
    {
        $this->createForeignKeys();
        factory(Book::class)->create();
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
        $book = factory(Book::class)->make();
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
        $book = factory(Book::class)->make();
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
        $this->withoutExceptionHandling();
        $this->createForeignKeys(3);
        $this->signIn();
        $book = factory(Book::class)->make();
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
