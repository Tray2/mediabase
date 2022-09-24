<?php

use App\Models\Author;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Series;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

beforeEach(function() {
    $this->author = Author::factory()->create();
    $this->genre = Genre::factory()->create();
    $this->format = Format::factory()->create();
    $this->series = Series::factory()->create();
    $this->publisher = Publisher::factory()->create();
    get(route('books.create'));
});

it('stores a valid book', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
    ->assertRedirect();
    assertDatabaseCount('books',1);
    assertDatabaseCount('author_book',1);
});

it('redirects and shows an error if the title is missing', function () {
    $bookData = [
        'title' => '',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect('/books/create')
        ->assertSessionHasErrorsIn('title');
    get(route('books.create'))
        ->assertSeeText('The title field is required.');
    assertDatabaseCount('books',0);
    assertDatabaseCount('author_book',0);
});

it('redirects and shows an error if the published year is missing', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => '',
        'isbn' => '9781398510784',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect('/books/create')
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.create'))
        ->assertSeeText('The published year field is required.');
    assertDatabaseCount('books',0);
    assertDatabaseCount('author_book',0);

});

it('shows an error if the published year is not numeric', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 'Nineteen Eighty Four',
        'isbn' => '9781398510784',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect('/books/create')
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.create'))
        ->assertSeeText('The published year must be a number.');
    assertDatabaseCount('books',0);
    assertDatabaseCount('author_book',0);
});

it('shows an error if the published year is less than four digits', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 123,
        'isbn' => '9781398510784',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect('/books/create')
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.create'))
        ->assertSeeText('The published year must have at least 4 digits.');
    assertDatabaseCount('books',0);
    assertDatabaseCount('author_book',0);
});

it('shows an error if the published year is more than four digits', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 12345,
        'isbn' => '9781398510784',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect('/books/create')
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.create'))
        ->assertSeeText('The published year must not have more than 4 digits.');
    assertDatabaseCount('books',0);
    assertDatabaseCount('author_book',0);
});

it('shows an error if the published year is more than a year into the future', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => Carbon::now()->addYear(2)->year,
        'isbn' => '9781398510784',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect('/books/create')
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.create'))
        ->assertSeeText('The published year must be between 1800 and ' . Carbon::now()->addYear(1)->year . '.');
    assertDatabaseCount('books',0);
    assertDatabaseCount('author_book',0);
});

it('shows an error if the isbn is missing', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect('/books/create')
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.create'))
        ->assertSeeText('The isbn field is required.');
    assertDatabaseCount('books',0);
    assertDatabaseCount('author_book',0);
});

it('shows an error if the isbn is not a valid isbn10 or isbn13' , function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '97813985100000',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect('/books/create')
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.create'))
        ->assertSeeText('The isbn must be a valid ISBN10 or ISBN13.');
    assertDatabaseCount('books',0);
    assertDatabaseCount('author_book',0);

});

it('shows an error if the blurb is missing', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => '',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect('/books/create')
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.create'))
        ->assertSeeText('The blurb field is required.');
    assertDatabaseCount('books',0);
    assertDatabaseCount('author_book',0);
});

it('shows an error if the blurb word count is less than three', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => 'This',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect('/books/create')
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.create'))
        ->assertSeeText('The blurb must be at least 3 words.');
    assertDatabaseCount('books',0);
    assertDatabaseCount('author_book',0);
});

it('shows an error if the part is missing and the book is not standalone', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => 'This is some boring text',
        'part' => '',
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect('/books/create')
        ->assertSessionHasErrorsIn('published_year');
    get(route('books.create'))
        ->assertSeeText('The part is required when book belongs to a series.');
    assertDatabaseCount('books',0);
    assertDatabaseCount('author_book',0);
});

it('stores a valid standalone book', function () {
    $standalone = Series::create([
        'name' => 'Standalone',
    ]);
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => 'This is some boring text',
        'part' => '',
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $standalone->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect('/books')
        ->assertSessionDoesntHaveErrors();
    assertDatabaseCount('books',1);
    assertDatabaseCount('author_book',1);
});

it('removes the part if the book is a standalone', function () {
    $standalone = Series::create([
        'name' => 'Standalone',
    ]);
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => 'This is some boring text',
        'part' => '1',
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $standalone->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect('/books')
        ->assertSessionDoesntHaveErrors();
    assertDatabaseCount('books',1);
    assertDatabaseCount('author_book',1);
    assertDatabaseHas('books',['part' => null]);
});

it('shows an error if the author is missing', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => 'This is some boring text',
        'part' => '1',
        'author' => '',
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect('/books/create')
        ->assertSessionHasErrorsIn('author');
    get(route('books.create'))
        ->assertSeeText('The author field is required.');

    assertDatabaseCount('books',0);
    assertDatabaseCount('author_book',0);
});

it('creates a new author if the one passed does not exist in the database', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => 'Jordan, Robert',
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect();
    assertDatabaseCount('books',1);
    assertDatabaseCount('author_book',1);
    assertDatabaseHas('authors', ['last_name' => 'Jordan', 'first_name' => 'Robert']);
});

it('shows an error if the genre is missing', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => '',
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect('/books/create')
        ->assertSessionHasErrorsIn('genre_name');
    get(route('books.create'))
        ->assertSeeText('The genre name field is required.');
    assertDatabaseCount('books',0);
    assertDatabaseCount('author_book',0);
});

it('creates a new genre if the one passed does not exist in the database', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => 'Fantasy',
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect();
    assertDatabaseCount('books',1);
    assertDatabaseCount('author_book',1);
    assertDatabaseHas('genres', ['name' => 'Fantasy']);
});

it('shows an error if the format is missing', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => '',
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect('/books/create')
        ->assertSessionHasErrorsIn('format_name');
    get(route('books.create'))
        ->assertSeeText('The format name field is required.');
    assertDatabaseCount('books',0);
    assertDatabaseCount('author_book',0);
});

it('creates a new format if the one passed does not exist in the database', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => '10"',
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect();
    assertDatabaseCount('books',1);
    assertDatabaseCount('author_book',1);
    assertDatabaseHas('formats', ['name' => '10"']);
});

it('shows an error if the series is missing', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => '',
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect('/books/create')
        ->assertSessionHasErrorsIn('format_name');
    get(route('books.create'))
        ->assertSeeText('The series name field is required.');
    assertDatabaseCount('books',0);
    assertDatabaseCount('author_book',0);
});

it('creates a new serie if the one passed does not exist in the database', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => 'The Great',
        'publisher_name' => $this->publisher->name,
    ];

    post(route('books.store', $bookData))
        ->assertRedirect();
    assertDatabaseCount('books',1);
    assertDatabaseCount('author_book',1);
    assertDatabaseHas('series', ['name' => 'The Great']);
});

it('shows an error if the publisher is missing', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => '',
    ];

    post(route('books.store', $bookData))
        ->assertRedirect('/books/create')
        ->assertSessionHasErrorsIn('format_name');
    get(route('books.create'))
        ->assertSeeText('The publisher name field is required.');
    assertDatabaseCount('books',0);
    assertDatabaseCount('author_book',0);
});

it('creates a new publisher if the one passed does not exist in the database', function () {
    $bookData = [
        'title' => 'Some Title',
        'published_year' => 1984,
        'isbn' => '9781398510784',
        'blurb' => 'Some boring text',
        'part' => 1,
        'author' => "{$this->author->last_name}, {$this->author->first_name}",
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => 'TOR',
    ];

    post(route('books.store', $bookData))
        ->assertRedirect();
    assertDatabaseCount('books',1);
    assertDatabaseCount('author_book',1);
    assertDatabaseHas('publishers', ['name' => 'TOR']);
});
