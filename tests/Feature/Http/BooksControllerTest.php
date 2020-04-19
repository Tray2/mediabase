<?php

namespace Tests\Feature\Http;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Author;
use App\AuthorBook;
use App\Book;
use App\BookCollection;
use App\BookRead;
use App\Format;
use App\Genre;
use App\Score;

class BooksControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $genre = '';
    protected $format = '';
    protected $author = '';

    protected function createForeignKeys($quantity = 1)
    {
        $this->genre = factory(Genre::class, $quantity)->create();
        $this->format = factory(Format::class, $quantity)->create();
        $this->author = factory(Author::class, $quantity)->create();
    }

    /**
    * @test
    */
    public function guests_can_list_all_books()
    {
        $this->createForeignKeys();

        $author1 = factory(Author::class)->create();

        $author2 = factory(Author::class)->create();


        $book1 = factory(Book::class)->create([
            'genre_id' => $this->genre[0]->id,
            'format_id' => $this->format[0]->id
        ]);

        $book2 = factory(Book::class)->create([
            'genre_id' => $this->genre[0]->id,
            'format_id' => $this->format[0]->id
        ]);

        factory(AuthorBook::class)->create([
            'book_id' => $book1->id,
            'author_id' => $author1->id
        ]);

        factory(AuthorBook::class)->create([
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

    /**
     *  @test
     */
    public function users_can_edit_a_book()
    {
        $this->signIn();

        $this->createForeignKeys(5);
        $book = factory(Book::class)->create();
        AuthorBook::create([
            'author_id' => 1,
            'book_id' => 1
        ]);

        $response = $this->get('/books/' . $book->id . '/edit');

        $response->assertSee('name="title"', false);
        $response->assertSee('name="series"', false);
        $response->assertSee('name="part"', false);
        $response->assertSee('name="isbn"', false);
        $response->assertSee('name="released"', false);
        $response->assertSee('name="reprinted"', false);
        $response->assertSee('name="pages"', false);
        $response->assertSee('name="blurb"', false);
        $response->assertSee('name="id"', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee('name="_method"', false);

        $response->assertSee('value="PUT"', false);
        $response->assertSee('value="' .$book->title . '"', false);
        $response->assertSee('value="' . $book->series . '"', false);
        $response->assertSee('value="' . $book->part . '"', false);
        $response->assertSee('value="' . $book->isbn . '"', false);
        $response->assertSee('value="' . $book->released . '"', false);
        $response->assertSee('value="' . $book->reprinted . '"', false);
        $response->assertSee('value="' . $book->pages . '"', false);
        $response->assertSee($book->blurb, false);
        $response->assertSee('input type="submit" value="Update"', false);

        $response->assertSee('name="genre_id"', false);
        $response->assertSee('name="format_id"', false);
        $response->assertSee('<option value="1" selected>' . $book->genre->genre . '</option>', false);
    }

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
            'type' => 'book'
        ]);

        $otherGenre = factory(Genre::class)->create();

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
    public function when_users_update_a_book_they_are_redirected_to_the_book_index_and_are_shown_a_success_message()
    {
        $this->createForeignKeys();
        $this->signIn();
        $book = factory(Book::class)->create();
        $book->title = 'Kalle';

        $response = $this->patch('/books/' . $book->id, $book->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/books');

        $response = $this->get('/books');
        $response->assertSee(e($book->title) . ' successfully updated.');
    }

    /**
    * @test
    */
    public function when_users_delete_a_book_they_are_redirected_to_the_book_index_and_are_shown_a_success_message()
    {
        $this->createForeignKeys();
        $this->signIn();

        $book = factory(Book::class)->create();

        $response = $this->delete('/books/' . $book->id);

        $response->assertStatus(302);
        $response->assertLocation('/books');

        $response = $this->get('/books');
        $response->assertSee(e($book->title) . ' successfully deleted.');
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
    public function book_rating_is_shown_in_book_index_view()
    {
        $this->createForeignKeys();
        $book = factory(Book::class)->create();

        factory(Score::class)->create([
            'book_id' => $book->id,
            'score' => '3'
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
        factory(Book::class)->create();

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
        factory(Book::class)->create();
        factory(BookCollection::class)->create(['user_id' => 1, 'book_id' => 1]);
        $response = $this->get('/books');
        $response->assertSee('Collected', false);
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
    public function read_books_are_shown_as_read()
    {
        $this->createForeignKeys();
        $this->signIn();
        factory(Book::class)->create();
        factory(BookRead::class)->create(['user_id' => 1, 'book_id' => 1]);
        $response = $this->get('/books');
        $response->assertSee('Read', false);
    }
}
