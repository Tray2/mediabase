<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Publisher;
use App\Models\Series;
use Carbon\Carbon;
use Database\Seeders\MediaTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

beforeEach(function() {
    $this->seed(MediaTypeSeeder::class);
    $mediaTypeId = MediaType::query()
        ->where('name', 'book')
        ->value('id');
    $this->author = Author::factory()->create();
    $this->genre = Genre::factory()->create([
        'media_type_id' => $mediaTypeId,
    ]);
    $this->format = Format::factory()->create([
        'media_type_id' => $mediaTypeId,
    ]);
    $this->series = Series::factory()->create();
    $this->publisher = Publisher::factory()->create();
    $this->book = Book::factory()->create([
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => 'Some boring text',
        'part' => 1,
    ]);
    $this->validBook = array_merge($this->book->toArray(),[
        'author' => ["{$this->author->last_name}, {$this->author->first_name}"],
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ]);
    get(route('books.edit', $this->book));
});


it('updates a valid book', function () {
    $validBook = $this->validBook;
    $validBook['title'] = 'Some New Title';
    put(route('books.update', $this->book), $validBook)
        ->assertRedirect(route('books.index'));
    assertDatabaseHas('books', ['title' => 'Some New Title']);
    assertDatabaseCount('author_book', 1);
    assertDatabaseCount('books', 1);
});

it('can update a book with multiple authors into on author', function () {
    $this->book->authors()->attach(Author::factory()->create());
    $this->book->authors()->attach(Author::factory()->create());
    assertDatabaseCount('author_book',2);

    $validBook = $this->validBook;
    $validBook['author'] = [
        "{$this->author->last_name}, {$this->author->first_name}",
    ];

    put(route('books.update', $this->book), $validBook)
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books',1);
    assertDatabaseCount('author_book',1);
});

it('redirects and shows an error if the title is missing', function () {
    $invalidBook = $this->validBook;
    $invalidBook['title'] = '';

    put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('title');
    get(route('books.edit', $this->book))
        ->assertSeeText('The title field is required.');
});

it('redirects and shows an error if the published year is missing', function () {
    $invalidBook =  $this->validBook;
    $invalidBook['published_year'] = '';

    put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.edit', $this->book))
        ->assertSeeText('The published year field is required.');
});

it('shows an error if the published year is not numeric', function () {
    $invalidBook = $this->validBook;
    $invalidBook['published_year'] = 'Nineteen Eighty Four';

    put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.edit', $this->book))
        ->assertSeeText('The published year must be a number.');
});

it('shows an error if the published year is less than four digits', function () {
    $invalidBook = $this->validBook;
    $invalidBook['published_year'] = 123;

    put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.edit', $this->book))
        ->assertSeeText('The published year must have at least 4 digits.');
});

it('shows an error if the published year is more than four digits', function () {
    $invalidBook = $this->validBook;
    $invalidBook['published_year'] = 12345;

    put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.edit', $this->book))
        ->assertSeeText('The published year must not have more than 4 digits.');
});

it('shows an error if the published year is more than a year into the future', function () {
    $invalidBook = $this->validBook;
    $invalidBook['published_year'] = Carbon::now()->addYear(2)->year;

    put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.edit', $this->book))
        ->assertSeeText('The published year must be between 1800 and ' . Carbon::now()->addYear(1)->year . '.');
});

it('shows an error if the isbn is missing', function () {
    $invalidBook = $this->validBook;
    $invalidBook['isbn'] = '';

    put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.edit', $this->book))
        ->assertSeeText('The isbn field is required.');
});

it('shows an error if the isbn is not a valid isbn10 or isbn13' , function () {
    $invalidBook = $this->validBook;
    $invalidBook['isbn'] = '97813985100000';

    put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.edit', $this->book))
        ->assertSeeText('The isbn must be a valid ISBN10 or ISBN13.');
});

it('shows an error if the blurb is missing', function () {
    $invalidBook = $this->validBook;
    $invalidBook['blurb'] = '';

    put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.edit', $this->book))
        ->assertSeeText('The blurb field is required.');
});

it('shows an error if the blurb word count is less than three', function () {
    $invalidBook = $this->validBook;
    $invalidBook['blurb'] = 'This';

    put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.edit', $this->book))
        ->assertSeeText('The blurb must be at least 3 words.');
});

it('shows an error if the part is missing and the book is not standalone', function () {
    $invalidBook = $this->validBook;
    $invalidBook['part'] = '';

    put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.edit', $this->book))
        ->assertSeeText('The part is required when book belongs to a series.');
});

it('updates a valid standalone book', function () {
    $standalone = Series::create([
        'name' => 'Standalone',
    ]);
    $validBook = $this->validBook;
    $validBook['part'] = '';
    $validBook['series_name'] = $standalone->name;

    put(route('books.update',$this->book), $validBook)
        ->assertRedirect('/books')
        ->assertSessionDoesntHaveErrors();
});

it('removes the part if the book is a standalone', function () {
    $standalone = Series::create([
        'name' => 'Standalone',
    ]);
    assertDatabaseHas('books',['part' => $this->book->part]);

    $validBook = $this->validBook;
    $validBook['series_name'] = $standalone->name;

    put(route('books.update', $this->book), $validBook)
        ->assertRedirect(route('books.index'))
        ->assertSessionDoesntHaveErrors();
    assertDatabaseHas('books',['part' => null]);
});

it('shows an error if the author is missing', function () {
    $invalidBook = $this->validBook;
    $invalidBook['author'] = '';

    put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('author');
    get(route('books.edit', $this->book))
        ->assertSeeText('The author field is required.');

});

it('creates a new author if the one passed does not exist in the database', function () {
    $validBook = $this->validBook;
    $validBook['author'] = ['Jordan, Robert'];

    put(route('books.update', $this->book), $validBook)
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books',1);
    assertDatabaseCount('author_book',1);
    assertDatabaseHas('authors', ['last_name' => 'Jordan', 'first_name' => 'Robert']);
});

it('shows an error if the genre is missing', function () {
    $invalidBook = $this->validBook;
    $invalidBook['genre_name'] = '';

    put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('genre_name');
    get(route('books.edit', $this->book))
        ->assertSeeText('The genre name field is required.');
});

it('creates a new genre if the one passed does not exist in the database', function () {
    $validBook = $this->validBook;
    $validBook['genre_name'] = 'Fantasy';

    put(route('books.update', $this->book), $validBook)
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books',1);
    assertDatabaseCount('author_book',1);
    assertDatabaseHas('genres', ['name' => 'Fantasy']);
});

it('shows an error if the format is missing', function () {
    $invalidBook = $this->validBook;
    $invalidBook['format_name'] = '';

    put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('format_name');
    get(route('books.edit', $this->book))
        ->assertSeeText('The format name field is required.');
});

it('creates a new format if the one passed does not exist in the database', function () {
    $validBook = $this->validBook;
    $validBook['format_name'] = 'Hardcover';

    put(route('books.update', $this->book), $validBook)
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books',1);
    assertDatabaseCount('author_book',1);
    assertDatabaseHas('formats', ['name' => 'Hardcover']);
});

it('shows an error if the series is missing', function () {
    $invalidBook = $this->validBook;
    $invalidBook['series_name'] = '';

    put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('format_name');
    get(route('books.edit', $this->book))
        ->assertSeeText('The series name field is required.');
});

it('creates a new serie if the one passed does not exist in the database', function () {
    $validBook = $this->validBook;
    $validBook['series_name'] = 'The Great';

    put(route('books.update', $this->book), $validBook)
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books',1);
    assertDatabaseCount('author_book',1);
    assertDatabaseHas('series', ['name' => 'The Great']);
});

it('shows an error if the publisher is missing', function () {
    $invalidBook = $this->validBook;
    $invalidBook['publisher_name'] = '';

    put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('format_name');
    get(route('books.edit', $this->book))
        ->assertSeeText('The publisher name field is required.');
});

it('creates a new publisher if the one passed does not exist in the database', function () {
    $validBook = $this->validBook;
    $validBook['publisher_name'] = 'TOR';

    put(route('books.update', $this->book), $validBook)
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books',1);
    assertDatabaseCount('author_book',1);
    assertDatabaseHas('publishers', ['name' => 'TOR']);
});

it('has the old values in the form if the validation fails', function () {
    $invalidBook = $this->validBook;
    $invalidBook['title'] = '';
    $formatPattern = '/<input(.)*value="' . $invalidBook['format_name'] . '"(.)*>/';
    $genrePattern = '/<input(.)*value="' . $invalidBook['genre_name'] . '"(.)*>/';
    $seriesPattern = '/<input(.)*value="' . $invalidBook['series_name'] . '"(.)*>/';
    $publisherPattern = '/<input(.)*value="' . $invalidBook['publisher_name'] . '"(.)*>/';
    $authorPattern = '/<input(.)*value="' . $invalidBook['author'][0] . '"(.)*>/';


    put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('title');
    $response = get(route('books.edit', $this->book))
        ->assertSeeText('The title field is required.')
        ->assertSee([
            'value="' . $this->validBook['published_year'],
            'value="' . $this->validBook['isbn'],
            $this->validBook['blurb'],
            'value="' . $this->validBook['part'],
        ], false);

    $this->assertMatchesRegularExpression($authorPattern, $response->content());
    $this->assertMatchesRegularExpression($formatPattern, $response->content());
    $this->assertMatchesRegularExpression($genrePattern, $response->content());
    $this->assertMatchesRegularExpression($seriesPattern, $response->content());
    $this->assertMatchesRegularExpression($publisherPattern, $response->content());
});

it('has the old title value in the form if the validation fails', function () {
    $invalidBook = $this->validBook;
    $invalidBook['blurb'] = '';

    put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('blurb');
    get(route('books.create'))
        ->assertSee([
            'value="' . $this->validBook['title']
        ], false);
});

it('can handle multiple authors when validation fails', function () {
    $author = Author::factory()->create();
    $invalidBook = $this->validBook;
    $invalidBook['author'] = [
        "{$this->author->last_name}, {$this->author->first_name}",
        "{$author->last_name}, {$author->first_name}",
    ];

    $invalidBook['title'] = '';
    $pattern1 = '/<input(.)*value="' . $invalidBook['author'][0] . '"(.)*>/';
    $pattern2 = '/<input(.)*value="' . $invalidBook['author'][1] . '"(.)*>/';
    put(route('books.update', $this->book), $invalidBook);
    $response = get(route('books.edit', $this->book));
    $this->assertMatchesRegularExpression($pattern1, $response->content());
    $this->assertMatchesRegularExpression($pattern2, $response->content());
});

