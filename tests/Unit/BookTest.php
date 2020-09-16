<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Score;
use App\Models\Genre;
use App\Models\Format;
use App\Models\Author;
use App\Models\BookCollection;
use App\Models\AuthorBook;
use App\Models\BookRead;

class BookTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Format::factory()->create(['media_type_id' => env('BOOKS')]);
        Genre::factory()->create(['media_type_id' => env('BOOKS')]);
    }

    /**
    * @test
    */
    public function it_gets_the_average_of_the_scores_for_the_book()
    {
        $book = Book::factory()->create();

        Score::factory()->create([
            'book_id' => $book->id,
            'score' => '4'
        ]);

        Score::factory()->create([
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
        $book = Book::factory()->make([
            'title' => 'THis iS tHe titLE'
        ]);
        $this->assertEquals('This Is The Title', $book->title);
    }

    /**
    * @test
    */
    public function no_series_given_results_in_standalone_value_is_required()
    {

        $book = Book::factory()->make([
            'series' => null
        ]);

        $this->assertEquals('Standalone', $book->series);
    }

    /**
    * @test
    */
    public function the_series_must_start_every_word_with_an_upper_case_letter()
    {
        $book = Book::factory()->make([
              'series' => 'THis iS tHe Series',
            ]);


        $this->assertEquals('This Is The Series', $book->series);
    }


    /**
     * @test
     * */
    public function it_can_list_all_the_books_in_a_series()
    {
        $book = Book::factory()->create([
            'title' => 'The Eye Of The World',
            'series' => 'The Wheel Of Time',
            'part' => '1'
        ]);
        Book::factory()->create([
            'title' => 'The Great Hunt',
            'series' => 'The Wheel Of Time',
            'part' => '2'
        ]);
        Book::factory()->create([
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
        $author = Author::factory()->create();

        $book1 = Book::factory()->create([
            'title' => 'The Wizards First Rule',
            'series' => 'The Sword Of Truth',
            'part' => '1'
        ]);
         $book2 = Book::factory()->create([
            'title' => 'Law Of Nines',
            'series' => 'Standalone',
            'part' => null
        ]);
        $book3 = Book::factory()->create([
            'title' => 'The Stone Of Tears',
            'series' => 'The Sword Of Truth',
            'part' => 2
        ]);

        AuthorBook::factory()->create([
            'book_id' => $book1->id,
            'author_id' => $author->id
        ]);

        AuthorBook::factory()->create([
            'book_id' => $book2->id,
            'author_id' => $author->id
        ]);

        AuthorBook::factory()->create([
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
        $author = Author::factory()->create();

        $book1 = Book::factory()->create([
            'title' => 'Law Of Nines',
            'series' => 'Standalone',
            'part' => null
        ]);
        $book2 = Book::factory()->create([
            'title' => 'Nest',
            'series' => 'Standalone',
            'part' => null,
            'released' => 2016
        ]);
        $book3 = Book::factory()->create([
            'title' => 'The Stone Of Tears',
            'series' => 'The Sword Of Truth',
            'part' => 2,
            'released' => 1996
        ]);

        AuthorBook::factory()->create([
            'book_id' => $book1->id,
            'author_id' => $author->id
        ]);

        AuthorBook::factory()->create([
            'book_id' => $book2->id,
            'author_id' => $author->id
        ]);

        AuthorBook::factory()->create([
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
        $this->signIn();

        $book = Book::factory()->make([
          'series' => 'Some Series'
        ]);

        $book->series = null;

        $this->assertEquals('Standalone', $book->series);
    }

    /**
    * @test
    */
    public function when_listing_all_books_they_are_alphabetically_sorted_by_authors_last_name()
    {
        $jordan = Author::factory()->create([
            'last_name' => 'Jordan',
            'first_name' => 'Robert'
        ]);
        $terry = Author::factory()->create([
            'last_name' => 'Goodkind',
            'first_name' => 'Terry'
        ]);
        $sarah = Author::factory()->create([
            'last_name' => 'Ash',
            'first_name' => 'Sarah'
        ]);

        $bookJordan = Book::factory()->create();
        $bookTerry = Book::factory()->create();
        $bookSarah = Book::factory()->create();

        AuthorBook::factory()->create([
            'book_id' => $bookTerry->id,
            'author_id' => $terry->id
        ]);
        AuthorBook::factory()->create([
            'book_id' => $bookJordan->id,
            'author_id' => $jordan->id
        ]);
        AuthorBook::factory()->create([
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
        $author = Author::factory()->create([
            'last_name' => 'Goodkind',
            'first_name' => 'Terry'
        ]);

        $book1 = Book::factory()->create([
            'title' => 'The Law Of Nines',
            'released' => 2009
        ]);

        $book2 = Book::factory()->create([
            'title' => 'The Wizards First Rule',
            'released' => 1994
        ]);

        $book3 = Book::factory()->create([
            'title' => 'Nest',
            'released' => 2016
        ]);

        AuthorBook::factory()->create([
            'book_id' => $book1->id,
            'author_id' => $author->id
        ]);

        AuthorBook::factory()->create([
            'book_id' => $book2->id,
            'author_id' => $author->id
        ]);

        AuthorBook::factory()->create([
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
        $author = Author::factory()->create([
            'last_name' => 'Goodkind',
            'first_name' => 'Terry'
        ]);

        $book1 = Book::factory()->create([
            'title' => 'The Law Of Nines',
            'series' => 'Standalone',
            'released' => 2009
        ]);

        $book2 = Book::factory()->create([
            'title' => 'The Wizards First Rule',
            'series' => 'The Sword Of Truth',
            'part' => 1,
            'released' => 1994
        ]);
        $book3 = Book::factory()->create([
            'title' => 'The Stone Of Tears',
            'series' => 'The Sword Of Truth',
            'part' => 2,
            'released' => 2010
        ]);

        AuthorBook::factory()->create([
            'book_id' => $book1->id,
            'author_id' => $author->id
        ]);

        AuthorBook::factory()->create([
            'book_id' => $book2->id,
            'author_id' => $author->id
        ]);

        AuthorBook::factory()->create([
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
        $author1 = Author::factory()->create([
            'first_name' => 'Robert',
            'last_name' => 'Jordan'
        ]);
        $author2 = Author::factory()->create([
            'first_name' => 'Brandon',
            'last_name' => 'Sanderson'
        ]);

        $book = Book::factory()->create();
        AuthorBook::factory()->create([
            'book_id' => 1,
            'author_id' => $author1->id
        ]);
        AuthorBook::factory()->create([
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

        $book = Book::factory()->create();

        $this->assertEquals(0, $book->inCollection());

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

        $book = Book::factory()->create();

        $this->assertEquals(0, $book->isRead());

        BookRead::create([
            'book_id' => 1,
            'user_id' => 1
        ]);

        $this->assertEquals(1, $book->isRead());
    }
}
