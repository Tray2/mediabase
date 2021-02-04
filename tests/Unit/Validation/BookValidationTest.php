<?php

namespace Tests\Unit\Validation;

use Tests\TestCase;
use App\Models\Author;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Book;
use Carbon\Carbon;

class BookValidationTest extends TestCase
{
    protected $author;
    protected $genre;
    protected $format;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signIn();
        $this->author = Author::factory()->create();
        $this->genre = Genre::factory()->create(['media_type_id' => env('BOOKS')]);
        $this->format = Format::factory()->create(['media_type_id' => env('BOOKS')]);
        Book::factory()->create();
    }

    /**
    * @test
    */
    public function a_valid_book_can_be_stored()
    {
        $book = Book::factory()->make([
            'author_id' => $this->author->id,
            'title' => 'The Eye Of The World',
            'series' => 'The Wheel Of Time',
            'part' => 1,
            'format_id' => $this->format->id,
            'genre_id' => $this->genre->id,
            'isbn' => '0812511816',
            'released' => 1990,
            'reprinted' => 1991,
            'pages' => 805,
            'blurb' => 'Some placeholder blurb just for our test.'
        ]);

        $this->post('/books', $book->toArray());

        $this->assertEquals(1, Book::where('title', 'The Eye Of The World')->count());
    }

    /**
     * @test
     * @dataProvider storeValidationProvider
     * @param $fieldValue
     * @param $field
     */
    public function store_validation_tests($field, $fieldValue)
    {
        $book = Book::factory()->make([
            $field => $fieldValue
        ]);

        $response = $this->post('/books', $book->toArray());
        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn($field);
    }

    public function storeValidationProvider()
    {
        return [
            'the title is required' => ['title', ''],
            'part must be numeric' => ['part', 'One'],
            'format_id is required' => ['format_id', ''],
            'format_id must exist in formats' => ['format_id', 100],
            'genre_id is required' => ['genre_id', ''],
            'genre_id must exist in genres' => ['genre_id', 100],
            'isbn is required' => ['isbn', ''],
            'invalid isbn10 cant be stored' => ['isbn', '123456789'],
            'invalid isbn13 cant be stored' => ['isbn', '9771234567890'],
            'released is required' => ['released', ''],
            'pages is required' => ['pages', ''],
            'pages must be numeric' => ['pages', 'Ten'],
            'blurb is required' => ['blurb', ''],
            'released cant be earlie than 1800' => ['released', 1799],
            'released cant be later than current year + 1' => ['released', Carbon::now()->addYear(2)->year],
            'reprinted cant be earlier than 1800' => ['reprinted',  1799],
            'reprinted cant be later than current year + 1' => ['reprinted', Carbon::now()->addYear(2)->year]
        ];
    }



    /**
    * @test
    */
    public function part_is_required_only_if_book_is_part_of_a_series()
    {
        Author::factory()->create();
        Genre::factory()->create(['media_type_id' => env('BOOKS')]);
        Format::factory()->create(['media_type_id' => env('BOOKS')]);

        $standalone = Book::factory()->make([
            'series' => null,
            'part' => null
        ]);

        $partOfSeries = Book::factory()->make([
            'series' => 'The Wheel Of Time',
             'part' => null
         ]);

        $this->post('/books', $standalone->toArray());
        $this->post('/books', $partOfSeries->toArray())->assertSessionHasErrors('part');
    }

    /**
     * @test
     * @dataProvider validIsbnProvider
     * @param $value
     */
    public function a_valid_isbn_is_stored($value)
    {
        $isbn = Book::factory()->make([
                'isbn' => $value
        ]);

        $this->post('/books', $isbn->toArray());
        $this->assertEquals(1, Book::where('isbn', $value)->count());
    }

    public function validIsbnProvider()
    {
        return [
            ['1934356131'],
            ['9781491936085'],
            ['034082455X']
        ];
    }

    /**
    * @test
    */
    public function reprinted_is_not_required()
    {
        $book = Book::factory()->make([
                'reprinted' => null
            ]);

        $this->post('/books', $book->toArray())->assertSessionDoesntHaveErrors('reprinted');
    }


    /** @test */
    public function a_valid_book_can_be_updated()
    {
        $book = Book::factory()->create([
          'title' => 'The Eye Of The World',
          'series' => 'The Wheel Of Time',
          'part' => 1,
          'format_id' => $this->format->id,
          'genre_id' => $this->genre->id,
          'isbn' => '0812511816',
          'released' => 1990,
          'reprinted' => 1991,
          'pages' => 805,
          'blurb' => 'Some placeholder blurb just for our test.'
        ]);

        $book->title = 'The Great Hunt';
        $book->part = 2;

        $this->put('/books/' . $book->id, $book->toArray());

        $this->assertEquals('The Great Hunt', $book->fresh()->title);
        $this->assertEquals(2, $book->fresh()->part);
    }

    /**
     * @test
     * @dataProvider storeValidationProvider
     * @dataProvider updateValidationProvider
     * @param $field
     * @param $fieldValue
     */
    public function updateValidations($field, $fieldValue)
    {
        $book = Book::factory()->create();
        $id = $book->id;
        $book[$field] = $fieldValue;
        $response = $this->put('/books/' . $id, $book->toArray());
        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn($field);
    }

    public function updateValidationProvider()
    {
        return [
            'id is recuired' => ['id', ''],
            'id must exist in records' => ['id', 100]
         ];
    }


    /** @test */
    public function part_is_required_if_book_is_part_of_series_when_updating()
    {
        $book = Book::factory()->create([
            'series' => 'The Wheel Of Time',
             'part' => 1
         ]);

        $book->part = null;

        $this->put('/books/' . $book->id, $book->toArray())->assertSessionHasErrors('part');
        $this->assertEquals(1, Book::where('part', 1)->count());
    }

    /** @test
     * @dataProvider validIsbnProvider
     * @param $value
     */
    public function a_valid_isbn_is_can_be_used_when_updating($value)
    {
        $book = Book::factory()->create();
        $book->isbn = $value;
        $this->put('/books/' . $book->id, $book->toArray());
        $this->assertEquals(1, Book::where('isbn', $value)->count());
    }

    /** @test */
    public function reprinted_is_not_required_when_updating()
    {
        $book = Book::factory()->create([
            'reprinted' => '2001'
        ]);
        $book->reprinted = null;


        $this->put('/books/' . $book->id, $book->toArray());
        $this->assertEquals(1, Book::where('reprinted', null)->count());
    }

    /**
     * @test
     **/
    public function a_user_can_delete_a_book()
    {
        $book = Book::factory()->create();


        $this->delete('/books/' . $book->id);
        $this->assertEquals(0, Book::where('title', $book->title)->count());
    }
}
