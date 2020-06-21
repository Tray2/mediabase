<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Book;
use App\Score;
use App\Genre;
use App\Format;
use App\Author;
use App\BookCollection;
use App\AuthorBook;
use App\BookRead;

class BookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        factory(Format::class)->create(['type' => 'books']);
        factory(Genre::class)->create(['type' => 'books']);
    }

    /**
    * @test
    */
    public function it_gets_the_average_of_the_scores_for_the_book()
    {
        factory(Author::class)->create();

        $book = factory(Book::class)->create();

        factory(Score::class)->create([
            'book_id' => $book->id,
            'score' => '4'
        ]);

        factory(Score::class)->create([
            'book_id' => $book->id,
            'score' => '2'
        ]);

        $this->assertEquals(3, $book->score);
    }


    /**
    * @test
    */
    public function the_title_must_start_every_word_with_an_upper_case_letter()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $author = factory(Author::class)->create();
        $book = factory(Book::class)->make([
            'title' => 'THis iS tHe titLE'
        ]);

        $book->author_id = $author->id;

        $this->post('/books', $book->toArray());
        $this->assertEquals(1, Book::where('title', 'This Is The Title')->count());
    }

    /**
    * @test
    */
    public function no_series_given_results_in_standalone_value_is_required()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $author = factory(Author::class)->create();
        $book = factory(Book::class)->make([
            'series' => null
        ]);

        $book->author_id = $author->id;

        $this->post('/books', $book->toArray());

        $this->assertEquals(1, Book::where('series', 'Standalone')->count());
    }

    /**
    * @test
    */
    public function the_series_must_start_every_word_with_an_upper_case_letter()
    {
        $this->signIn();
        $this->withExceptionHandling();

        factory(Author::class)->create();

        $book = factory(Book::class)->make([
              'series' => 'THis iS tHe Series',
            ]);

        $response = $this->post('/books', $book->toArray());

        $this->assertEquals(1, Book::where('series', 'This Is The Series')->count());
    }


    /**
     * @test
     * */
    public function it_can_list_all_the_books_in_a_series()
    {
        factory(Author::class)->create();
        $book = factory(Book::class)->create([
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
        $othersInSeries = $book->otherInSeries();
        $this->assertEquals($othersInSeries[0]->title, 'The Eye Of The World');
        $this->assertEquals($othersInSeries[1]->title, 'The Great Hunt');
        $this->assertEquals($othersInSeries[2]->title, 'The Dragon Reborn');
    }

    /**
     *  @test
     */
    public function it_can_list_all_the_books_by_the_author_that_are_not_part_of_the_series_being_viewed()
    {
        $author = factory(Author::class)->create();

        $book1 = factory(Book::class)->create([
            'title' => 'The Wizards First Rule',
            'series' => 'The Sword Of Truth',
            'part' => '1'
        ]);
         $book2 = factory(Book::class)->create([
            'title' => 'Law Of Nines',
            'series' => 'Standalone',
            'part' => null
        ]);
        $book3 = factory(Book::class)->create([
            'title' => 'The Stone Of Tears',
            'series' => 'The Sword Of Truth',
            'part' => 2
        ]);

        factory(AuthorBook::class)->create([
            'book_id' => $book1->id,
            'author_id' => $author->id
        ]);

        factory(AuthorBook::class)->create([
            'book_id' => $book2->id,
            'author_id' => $author->id
        ]);

        factory(AuthorBook::class)->create([
            'book_id' => $book3->id,
            'author_id' => $author->id
        ]);

        $othersBooks = $book1->otherBooks();
        $this->assertEquals($othersBooks[0]->title, 'Law Of Nines');
        $this->assertFalse(isset($othersBooks[1]->title));
        $this->assertFalse(isset($othersBooks[2]->title));
    }

    /** @test */
    public function it_can_list_all_the_books_by_the_author_except_the_one_being_viewed_when_the_series_is_standalone()
    {
        $author = factory(Author::class)->create();

        $book1 = factory(Book::class)->create([
            'title' => 'Law Of Nines',
            'series' => 'Standalone',
            'part' => null
        ]);
        $book2 = factory(Book::class)->create([
            'title' => 'Nest',
            'series' => 'Standalone',
            'part' => null,
            'released' => 2016
        ]);
        $book3 = factory(Book::class)->create([
            'title' => 'The Stone Of Tears',
            'series' => 'The Sword Of Truth',
            'part' => 2,
            'released' => 1996
        ]);

        factory(AuthorBook::class)->create([
            'book_id' => $book1->id,
            'author_id' => $author->id
        ]);

        factory(AuthorBook::class)->create([
            'book_id' => $book2->id,
            'author_id' => $author->id
        ]);

        factory(AuthorBook::class)->create([
            'book_id' => $book3->id,
            'author_id' => $author->id
        ]);

        $othersBooks = $book1->otherBooks();
        $this->assertEquals($othersBooks[0]->title, 'The Stone Of Tears');
        $this->assertEquals($othersBooks[1]->title, 'Nest');
        $this->assertFalse(isset($othersBooks[2]->title));
    }

    /** @test */
    public function no_series_given_results_in_standalone_value_when_updating()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        factory(Author::class)->create();
        factory(Author::class)->create();

        $book = factory(Book::class)->create([
          'series' => 'Some Series'
        ]);

        $book->series = null;

        $this->put('/books/' . $book->id, $book->toArray());

        $this->assertEquals(1, Book::where('series', 'Standalone')->count());
    }

    /**
    * @test
    */
    public function when_listing_all_books_they_are_alphabetically_sorted_by_authors_last_name()
    {
        $jordan = factory(Author::class)->create([
            'last_name' => 'Jordan',
            'first_name' => 'Robert'
        ]);
        $terry = factory(Author::class)->create([
            'last_name' => 'Goodkind',
            'first_name' => 'Terry'
        ]);
        $sarah = factory(Author::class)->create([
            'last_name' => 'Ash',
            'first_name' => 'Sarah'
        ]);

        $bookJordan = factory(Book::class)->create();
        $bookTerry = factory(Book::class)->create();
        $bookSarah = factory(Book::class)->create();

        factory(AuthorBook::class)->create([
            'book_id' => $bookTerry->id,
            'author_id' => $terry->id
        ]);
        factory(AuthorBook::class)->create([
            'book_id' => $bookJordan->id,
            'author_id' => $jordan->id
        ]);
        factory(AuthorBook::class)->create([
            'book_id' => $bookSarah->id,
            'author_id' => $sarah->id
        ]);
        $response = $this->get('/books');

        $response->assertSeeInOrder([
            'Ash, Sarah',
            'Goodkind, Terry',
            'Jordan, Robert'
        ]);
    }

    /**
    * @test
    */
    public function books_by_the_same_author_is_sorted_by_relase_year()
    {
        $author = factory(Author::class)->create([
            'last_name' => 'Goodkind',
            'first_name' => 'Terry'
        ]);

        $book1 = factory(Book::class)->create([
            'title' => 'The Law Of Nines',
            'released' => 2009
        ]);

        $book2 = factory(Book::class)->create([
            'title' => 'The Wizards First Rule',
            'released' => 1994
        ]);

        $book3 = factory(Book::class)->create([
            'title' => 'Nest',
            'released' => 2016
        ]);

        factory(AuthorBook::class)->create([
            'book_id' => $book1->id,
            'author_id' => $author->id
        ]);

        factory(AuthorBook::class)->create([
            'book_id' => $book2->id,
            'author_id' => $author->id
        ]);

        factory(AuthorBook::class)->create([
            'book_id' => $book3->id,
            'author_id' => $author->id
        ]);

        $response = $this->get('/books');

        $response->assertSeeInOrder([
            'The Wizards First Rule',
            'The Law Of Nines',
            'Nest'
        ]);
    }

   /**
    * @test
    */
    public function books_in_the_same_series_is_sorted_by_the_first_book_in_the_series_release_year_then_by_part()
    {
        $author = factory(Author::class)->create([
            'last_name' => 'Goodkind',
            'first_name' => 'Terry'
        ]);

        $book1 = factory(Book::class)->create([
            'title' => 'The Law Of Nines',
            'series' => 'Standalone',
            'released' => 2009
        ]);

        $book2 = factory(Book::class)->create([
            'title' => 'The Wizards First Rule',
            'series' => 'The Sword Of Truth',
            'part' => 1,
            'released' => 1994
        ]);
        $book3 = factory(Book::class)->create([
            'title' => 'The Stone Of Tears',
            'series' => 'The Sword Of Truth',
            'part' => 2,
            'released' => 2010
        ]);

        factory(AuthorBook::class)->create([
            'book_id' => $book1->id,
            'author_id' => $author->id
        ]);

        factory(AuthorBook::class)->create([
            'book_id' => $book2->id,
            'author_id' => $author->id
        ]);

        factory(AuthorBook::class)->create([
            'book_id' => $book3->id,
            'author_id' => $author->id
        ]);


        $response = $this->get('/books');

        $response->assertSeeInOrder([
            'The Wizards First Rule',
            'The Stone Of Tears',
            'The Law Of Nines',
        ]);
    }

    /**
    * @test
    */
    public function books_can_have_more_than_one_author()
    {
        $author1 = factory(Author::class)->create([
            'first_name' => 'Robert',
            'last_name' => 'Jordan'
        ]);
        $author2 = factory(Author::class)->create([
            'first_name' => 'Brandon',
            'last_name' => 'Sanderson'
        ]);

        $book = factory(Book::class)->create();
        factory(AuthorBook::class)->create([
            'book_id' => 1,
            'author_id' => $author1->id
        ]);
        factory(AuthorBook::class)->create([
            'book_id' => 1,
            'author_id' => $author2->id
        ]);

        $this->assertEquals('Jordan, Robert', $book->author[0]->name);
        $this->assertEquals('Sanderson, Brandon', $book->author[1]->name);
    }

    /**
    * @test
    */
    public function it_returns_how_many_of_the_book_you_have_in_your_collection()
    {
        $this->signIn();
        factory(Author::class)->create();

        $book = factory(Book::class)->create();

        $this->assertEquals(0, $book->inCollection());

        AuthorBook::create([
            'book_id' => 1,
            'author_id' => 1
        ]);

        BookCollection::create([
            'book_id' => 1,
            'user_id' => 1
        ]);

        $this->assertEquals(1, $book->inCollection());
    }

    /**
    * @test
    */
    public function it_returns_how_many_times_you_have_read_the_book()
    {
        $this->signIn();
        factory(Author::class)->create();

        $book = factory(Book::class)->create();

        $this->assertEquals(0, $book->isRead());

        AuthorBook::create([
            'book_id' => 1,
            'author_id' => 1
        ]);

        BookRead::create([
            'book_id' => 1,
            'user_id' => 1
        ]);

        $this->assertEquals(1, $book->isRead());
    }


}
