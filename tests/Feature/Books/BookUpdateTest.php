<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Publisher;
use App\Models\Series;
use App\Models\User;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use Sinnbeck\DomAssertions\Asserts\AssertForm;

beforeEach(function () {
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
    $this->validBook = array_merge($this->book->toArray(), [
        'author' => ["{$this->author->last_name}, {$this->author->first_name}"],
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'series_name' => $this->series->name,
        'publisher_name' => $this->publisher->name,
    ]);
    $this->user = User::factory()->create();
    actingAs($this->user)->get(route('books.edit', $this->book));
});

it('updates a valid book', function () {
    $validBook = $this->validBook;
    $validBook['title'] = 'Some New Title';
    actingAs($this->user)->put(route('books.update', $this->book), $validBook)
        ->assertRedirect(route('books.index'));
    assertDatabaseHas('books', ['title' => 'Some New Title']);
    assertDatabaseCount('author_book', 1);
    assertDatabaseCount('books', 1);
});

it('can update a book with multiple authors into on author', function () {
    $this->book->authors()->attach(Author::factory()->create());
    $this->book->authors()->attach(Author::factory()->create());
    assertDatabaseCount('author_book', 2);

    $validBook = $this->validBook;
    $validBook['author'] = [
        "{$this->author->last_name}, {$this->author->first_name}",
    ];

    actingAs($this->user)->put(route('books.update', $this->book), $validBook)
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books', 1);
    assertDatabaseCount('author_book', 1);
});

it('updates a valid standalone book', function () {
    $this->withoutExceptionHandling();
    $standalone = Series::create([
        'name' => 'Standalone',
    ]);
    $validBook = $this->validBook;
    $validBook['part'] = null;
    $validBook['series_name'] = $standalone->name;

    actingAs($this->user)->put(route('books.update', $this->book), $validBook)
        ->assertRedirect('/books')
        ->assertSessionDoesntHaveErrors();
});

it('removes the part if the book is a standalone', function () {
    $standalone = Series::create([
        'name' => 'Standalone',
    ]);
    assertDatabaseHas('books', ['part' => $this->book->part]);

    $validBook = $this->validBook;
    $validBook['series_name'] = $standalone->name;

    actingAs($this->user)->put(route('books.update', $this->book), $validBook)
        ->assertRedirect(route('books.index'))
        ->assertSessionDoesntHaveErrors();
    assertDatabaseHas('books', ['part' => null]);
});

it('creates a new author if the one passed does not exist in the database', function () {
    $validBook = $this->validBook;
    $validBook['author'] = ['Jordan, Robert'];

    actingAs($this->user)->put(route('books.update', $this->book), $validBook)
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books', 1);
    assertDatabaseCount('author_book', 1);
    assertDatabaseHas('authors', ['last_name' => 'Jordan', 'first_name' => 'Robert']);
});

it('creates a new genre if the one passed does not exist in the database', function () {
    $validBook = $this->validBook;
    $validBook['genre_name'] = 'Fantasy';

    actingAs($this->user)->put(route('books.update', $this->book), $validBook)
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books', 1);
    assertDatabaseCount('author_book', 1);
    assertDatabaseHas('genres', ['name' => 'Fantasy']);
});

it('creates a new format if the one passed does not exist in the database', function () {
    $validBook = $this->validBook;
    $validBook['format_name'] = 'Hardcover';

    actingAs($this->user)->put(route('books.update', $this->book), $validBook)
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books', 1);
    assertDatabaseCount('author_book', 1);
    assertDatabaseHas('formats', ['name' => 'Hardcover']);
});

it('creates a new serie if the one passed does not exist in the database', function () {
    $validBook = $this->validBook;
    $validBook['series_name'] = 'The Great';

    actingAs($this->user)->put(route('books.update', $this->book), $validBook)
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books', 1);
    assertDatabaseCount('author_book', 1);
    assertDatabaseHas('series', ['name' => 'The Great']);
});

it('creates a new publisher if the one passed does not exist in the database', function () {
    $validBook = $this->validBook;
    $validBook['publisher_name'] = 'TOR';

    actingAs($this->user)->put(route('books.update', $this->book), $validBook)
        ->assertRedirect(route('books.index'));
    assertDatabaseCount('books', 1);
    assertDatabaseCount('author_book', 1);
    assertDatabaseHas('publishers', ['name' => 'TOR']);
});

it('has the old values in the form if the validation fails', function () {
    $invalidBook = $this->validBook;
    $invalidBook['title'] = '';
    actingAs($this->user)->put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('title');
    actingAs($this->user)->get(route('books.edit', $this->book))
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

    actingAs($this->user)->put(route('books.update', $this->book), $invalidBook)
        ->assertRedirect(route('books.edit', $this->book))
        ->assertSessionHasErrorsIn('blurb');

    actingAs($this->user)->get(route('books.create'))
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
    actingAs($this->user)->put(route('books.update', $this->book), $invalidBook);
    actingAs($this->user)->get(route('books.edit', $this->book))
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
