<?php

use App\Models\Author;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Publisher;
use App\Models\Series;
use Carbon\Carbon;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use Sinnbeck\DomAssertions\Asserts\AssertForm;

uses(FastRefreshDatabase::class);

beforeEach(function () {
    $mediaTypeId = MediaType::query()
        ->where('name', 'book')
        ->value('id');
    $this->author = Author::factory()->create();
    $this->genre = Genre::factory()->create(['media_type_id' => $mediaTypeId]);
    $this->format = Format::factory()->create(['media_type_id' => $mediaTypeId]);
    $this->series = Series::factory()->create();
    $this->publisher = Publisher::factory()->create();
    $this->validBook = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => ["{$this->author->last_name}, {$this->author->first_name}"],
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];
    get(route('books.create'));
});

it('stores a valid book', function () {
    post(route('books.store', $this->validBook))
    ->assertRedirect(route('books.index'));
    assertDatabaseCount('books', 1);
    assertDatabaseCount('author_book', 1);
});

it('stores a valid book with multiple authors', function () {
    $author = Author::factory()->create();
    $validBook = $this->validBook;
    $validBook['author'] = [
        "{$this->author->last_name}, {$this->author->first_name}",
        "{$author->last_name}, {$author->first_name}",
    ];

    post(route('books.store', $validBook))
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books', 1);
    assertDatabaseCount('author_book', 2);
});

it('redirects and shows an error if the title is missing', function () {
    $invalidBook = $this->validBook;
    $invalidBook['title'] = '';

    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'))
        ->assertSessionHasErrorsIn('title');
    get(route('books.create'))
        ->assertSeeText('The title field is required.');
    assertDatabaseCount('books', 0);
    assertDatabaseCount('author_book', 0);
});

it('redirects and shows an error if the published year is missing', function () {
    $invalidBook = $this->validBook;
    $invalidBook['published_year'] = '';

    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'))
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.create'))
        ->assertSeeText('The published year field is required.');
    assertDatabaseCount('books', 0);
    assertDatabaseCount('author_book', 0);
});

it('shows an error if the published year is not numeric', function () {
    $invalidBook = $this->validBook;
    $invalidBook['published_year'] = 'Nineteen Eighty Four';

    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'))
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.create'))
        ->assertSeeText('The published year must be a number.');
    assertDatabaseCount('books', 0);
    assertDatabaseCount('author_book', 0);
});

it('shows an error if the published year is less than four digits', function () {
    $invalidBook = $this->validBook;
    $invalidBook['published_year'] = 123;

    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'))
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.create'))
        ->assertSeeText('The published year must have at least 4 digits.');
    assertDatabaseCount('books', 0);
    assertDatabaseCount('author_book', 0);
});

it('shows an error if the published year is more than four digits', function () {
    $invalidBook = $this->validBook;
    $invalidBook['published_year'] = 12345;

    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'))
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.create'))
        ->assertSeeText('The published year must not have more than 4 digits.');
    assertDatabaseCount('books', 0);
    assertDatabaseCount('author_book', 0);
});

it('shows an error if the published year is more than a year into the future', function () {
    $invalidBook = $this->validBook;
    $invalidBook['published_year'] = Carbon::now()->addYear(2)->year;

    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'))
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.create'))
        ->assertSeeText('The published year must be between 1800 and '.Carbon::now()->addYear(1)->year.'.');
    assertDatabaseCount('books', 0);
    assertDatabaseCount('author_book', 0);
});

it('shows an error if the isbn is missing', function () {
    $invalidBook = $this->validBook;
    $invalidBook['isbn'] = '';

    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'))
        ->assertSessionHasErrorsIn('isbn');
    get(route('books.create'))
        ->assertSeeText('The isbn field is required.');
    assertDatabaseCount('books', 0);
    assertDatabaseCount('author_book', 0);
});

it('shows an error if the isbn is not a valid isbn10 or isbn13', function () {
    $invalidBook = $this->validBook;
    $invalidBook['isbn'] = '97813985100000';

    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'))
        ->assertSessionHasErrorsIn('isbn');
    get(route('books.create'))
        ->assertSeeText('The isbn must be a valid ISBN10 or ISBN13.');
    assertDatabaseCount('books', 0);
    assertDatabaseCount('author_book', 0);
});

it('shows an error if the blurb is missing', function () {
    $invalidBook = $this->validBook;
    $invalidBook['blurb'] = '';

    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'))
        ->assertSessionHasErrorsIn('blurb');
    get(route('books.create'))
        ->assertSeeText('The blurb field is required.');
    assertDatabaseCount('books', 0);
    assertDatabaseCount('author_book', 0);
});

it('shows an error if the blurb word count is less than three', function () {
    $invalidBook = $this->validBook;
    $invalidBook['blurb'] = 'This';

    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'))
        ->assertSessionHasErrorsIn('blurb');
    get(route('books.create'))
        ->assertSeeText('The blurb must be at least 3 words.');
    assertDatabaseCount('books', 0);
    assertDatabaseCount('author_book', 0);
});

it('shows an error if the part is missing and the book is not standalone', function () {
    $invalidBook = $this->validBook;
    $invalidBook['part'] = '';

    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'))
        ->assertSessionHasErrorsIn('part');
    get(route('books.create'))
        ->assertSeeText('The part is required when book belongs to a series.');
    assertDatabaseCount('books', 0);
    assertDatabaseCount('author_book', 0);
});

it('stores a valid standalone book', function () {
    $standalone = Series::create([
        'name' => 'Standalone',
    ]);
    $validBook = $this->validBook;
    $validBook['part'] = '';
    $validBook['series_name'] = $standalone->name;

    post(route('books.store', $validBook))
        ->assertRedirect('/books')
        ->assertSessionDoesntHaveErrors();
    assertDatabaseCount('books', 1);
    assertDatabaseCount('author_book', 1);
});

it('removes the part if the book is a standalone', function () {
    $standalone = Series::create([
        'name' => 'Standalone',
    ]);
    $validBook = $this->validBook;
    $validBook['series_name'] = $standalone->name;

    post(route('books.store', $validBook))
        ->assertRedirect(route('books.index'))
        ->assertSessionDoesntHaveErrors();
    assertDatabaseCount('books', 1);
    assertDatabaseCount('author_book', 1);
    assertDatabaseHas('books', ['part' => null]);
});

it('shows an error if the author is missing', function () {
    $invalidBook = $this->validBook;
    $invalidBook['author'] = '';

    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'))
        ->assertSessionHasErrorsIn('author');
    get(route('books.create'))
        ->assertSeeText('The author field is required.');

    assertDatabaseCount('books', 0);
    assertDatabaseCount('author_book', 0);
});

it('creates a new author if the one passed does not exist in the database', function () {
    $validBook = $this->validBook;
    $validBook['author'] = ['Jordan, Robert'];

    post(route('books.store', $validBook))
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books', 1);
    assertDatabaseCount('author_book', 1);
    assertDatabaseHas('authors', ['last_name' => 'Jordan', 'first_name' => 'Robert']);
});

it('shows an error if the genre is missing', function () {
    $invalidBook = $this->validBook;
    $invalidBook['genre_name'] = '';

    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'))
        ->assertSessionHasErrorsIn('genre_name');
    get(route('books.create'))
        ->assertSeeText('The genre name field is required.');
    assertDatabaseCount('books', 0);
    assertDatabaseCount('author_book', 0);
});

it('creates a new genre if the one passed does not exist in the database', function () {
    $validBook = $this->validBook;
    $validBook['genre_name'] = 'Fantasy';

    post(route('books.store', $validBook))
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books', 1);
    assertDatabaseCount('author_book', 1);
    assertDatabaseHas('genres', ['name' => 'Fantasy']);
});

it('shows an error if the format is missing', function () {
    $invalidBook = $this->validBook;
    $invalidBook['format_name'] = '';

    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'))
        ->assertSessionHasErrorsIn('format_name');
    get(route('books.create'))
        ->assertSeeText('The format name field is required.');
    assertDatabaseCount('books', 0);
    assertDatabaseCount('author_book', 0);
});

it('creates a new format if the one passed does not exist in the database', function () {
    $validBook = $this->validBook;
    $validBook['format_name'] = 'Hardcover';

    post(route('books.store', $validBook))
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books', 1);
    assertDatabaseCount('author_book', 1);
    assertDatabaseHas('formats', ['name' => 'Hardcover']);
});

it('shows an error if the series is missing', function () {
    $invalidBook = $this->validBook;
    $invalidBook['series_name'] = '';

    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'))
        ->assertSessionHasErrorsIn('format_name');
    get(route('books.create'))
        ->assertSeeText('The series name field is required.');
    assertDatabaseCount('books', 0);
    assertDatabaseCount('author_book', 0);
});

it('creates a new serie if the one passed does not exist in the database', function () {
    $validBook = $this->validBook;
    $validBook['series_name'] = 'The Great';

    post(route('books.store', $validBook))
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books', 1);
    assertDatabaseCount('author_book', 1);
    assertDatabaseHas('series', ['name' => 'The Great']);
});

it('shows an error if the publisher is missing', function () {
    $invalidBook = $this->validBook;
    $invalidBook['publisher_name'] = '';

    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'))
        ->assertSessionHasErrorsIn('format_name');
    get(route('books.create'))
        ->assertSeeText('The publisher name field is required.');
    assertDatabaseCount('books', 0);
    assertDatabaseCount('author_book', 0);
});

it('creates a new publisher if the one passed does not exist in the database', function () {
    $validBook = $this->validBook;
    $validBook['publisher_name'] = 'TOR';

    post(route('books.store', $validBook))
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books', 1);
    assertDatabaseCount('author_book', 1);
    assertDatabaseHas('publishers', ['name' => 'TOR']);
});

it('has the old values in the form if the validation fails', function () {
    $invalidBook = $this->validBook;
    $invalidBook['title'] = '';

    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'))
        ->assertSessionHasErrorsIn('title');
    get(route('books.create'))
        ->assertOk()
        ->assertSeeText('The title field is required.')
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'name' => 'published_year',
                'value' => $this->validBook['published_year'],
            ])
                ->containsInput([
                    'name' => 'isbn',
                    'value' => $this->validBook['isbn'],
                ])
                ->containsInput([
                    'name' => 'part',
                    'value' => $this->validBook['part'],
                ])
                ->contains('textarea', [
                    'name' => 'blurb',
                    'value' => $this->validBook['blurb'],
                ])
                ->containsInput([
                    'name' => 'format_name',
                    'value' => $this->validBook['format_name'],
                ])
                ->containsInput([
                    'name' => 'author[]',
                    'value' => $this->validBook['author'][0],
                ])
                ->containsInput([
                    'name' => 'genre_name',
                    'value' => $this->validBook['genre_name'],
                ])
                ->containsInput([
                    'name' => 'series_name',
                    'value' => $this->validBook['series_name'],
                ])
                ->containsInput([
                    'name' => 'publisher_name',
                    'value' => $this->validBook['publisher_name'],
                ]);
        });
});

it('has the old title value in the form if the validation fails', function () {
    $invalidBook = $this->validBook;
    $invalidBook['blurb'] = '';

    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'))
        ->assertSessionHasErrorsIn('title');
    get(route('books.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'name' => 'title',
                'value' => $this->validBook['title'],
            ]);
        });
});

it('can handle multiple authors when validation fails', function () {
    $author = Author::factory()->create();
    $invalidBook = $this->validBook;
    $invalidBook['author'] = [
        "{$this->author->last_name}, {$this->author->first_name}",
        "{$author->last_name}, {$author->first_name}",
    ];

    $invalidBook['title'] = '';
    post(route('books.store', $invalidBook));
    get(route('books.create'))
    ->assertOk()
    ->assertFormExists(fn (AssertForm $form) => $form->containsInput([
        'name' => 'author[]',
        'value' => $invalidBook['author'][0],
    ])
            ->containsInput([
                'name' => 'author[]',
                'value' => $invalidBook['author'][1],
            ])
    );
});
