<?php

use App\Models\Author;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Publisher;
use App\Models\Series;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use Sinnbeck\DomAssertions\Asserts\AssertForm;

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

it('shows an error if the validation fails', function () {
    $invalidBook = $this->validBook;
    $invalidBook['title'] = '';
    post(route('books.store', $invalidBook))
        ->assertRedirect(route('books.create'));
    get(route('books.create'))
        ->assertSeeText('The title field is required.');
});

it('stores a valid standalone book', function () {
    $standalone = Series::create([
        'name' => 'Standalone',
    ]);
    $validBook = $this->validBook;
    $validBook['part'] = null;
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

it('creates a new author if the one passed does not exist in the database', function () {
    $validBook = $this->validBook;
    $validBook['author'] = ['Jordan, Robert'];

    post(route('books.store', $validBook))
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books', 1);
    assertDatabaseCount('author_book', 1);
    assertDatabaseHas('authors', ['last_name' => 'Jordan', 'first_name' => 'Robert']);
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

it('creates a new format if the one passed does not exist in the database', function () {
    $validBook = $this->validBook;
    $validBook['format_name'] = 'Hardcover';

    post(route('books.store', $validBook))
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books', 1);
    assertDatabaseCount('author_book', 1);
    assertDatabaseHas('formats', ['name' => 'Hardcover']);
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
