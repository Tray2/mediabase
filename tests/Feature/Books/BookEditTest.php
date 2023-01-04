<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Publisher;
use App\Models\Series;
use function Pest\Laravel\get;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use Sinnbeck\DomAssertions\Asserts\AssertDatalist;
use Sinnbeck\DomAssertions\Asserts\AssertForm;

uses(FastRefreshDatabase::class);

beforeEach(function () {
    $this->mediaTypeId = MediaType::query()
        ->where('name', 'book')
        ->value('id');
    $this->book = Book::factory()->create();
});

it('can show books.edit page', function () {
    get(route('books.edit', $this->book))
        ->assertOk();
});

it('contains an update form with the necessary parts for laravel', function () {
    get(route('books.edit', $this->book))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->hasMethod('post')
                ->hasAction(route('books.update', $this->book))
                ->hasSpoofMethod('put')
                ->hasCSRF();
        });
});

it('contains a title field', function () {
    get(route('books.edit', $this->book))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'title',
            ])
            ->containsInput([
                'name' => 'title',
                'id' => 'title',
                'value' => $this->book->title,
            ]);
        });
});

it('contains a published_year field', function () {
    get(route('books.edit', $this->book))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'published_year',
            ])
                ->containsInput([
                    'name' => 'published_year',
                    'id' => 'published_year',
                    'value' => $this->book->published_year,
                ]);
        });
});

it('contains an author field', function () {
    $this->book->authors()->attach(Author::factory()->create());
    get(route('books.edit', $this->book))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'author',
            ])
                ->containsInput([
                    'name' => 'author[]',
                    'id' => 'author',
                    'list' => 'authors',
                    'value' => $this->book->authors[0]->last_name.', '.$this->book->authors[0]->first_name,
                ])
                ->containsDatalist([
                    'id' => 'authors',
                ]);
        });
});

it('contains a format field', function () {
    get(route('books.edit', $this->book))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'format',
            ])
                ->containsInput([
                    'name' => 'format_name',
                    'id' => 'format',
                    'list' => 'formats',
                    'value' => $this->book->format->name,
                ])
                ->containsDatalist([
                    'id' => 'formats',
                ]);
        });
});

it('contains a genre field', function () {
    get(route('books.edit', $this->book))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'genre',
            ])
                ->containsInput([
                    'name' => 'genre_name',
                    'id' => 'genre',
                    'list' => 'genres',
                    'value' => $this->book->genre->name,
                ])
                ->containsDatalist([
                    'id' => 'genres',
                ]);
        });
});

it('contains an isbn field', function () {
    get(route('books.edit', $this->book))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'isbn',
            ])
                ->containsInput([
                    'name' => 'isbn',
                    'id' => 'isbn',
                    'value' => $this->book->isbn,
                ]);
        });
});

it('contains a blurb text area', function () {
    get(route('books.edit', $this->book))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'blurb',
            ])
                ->containsTextarea([
                    'name' => 'blurb',
                    'id' => 'blurb',
                    'value' => $this->book->blurb,
                    'text' => $this->book->blurb,
                ]);
        });
});

it('contains a series field', function () {
    get(route('books.edit', $this->book))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'series',
            ])
                ->containsInput([
                    'name' => 'series_name',
                    'id' => 'series',
                    'list' => 'series-list',
                    'value' => $this->book->series->name,
                ])
                ->containsDatalist([
                    'id' => 'series-list',
                ]);
        });
});

it('contains a part field', function () {
    get(route('books.edit', $this->book))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'part',
            ])
                ->containsInput([
                    'name' => 'part',
                    'id' => 'part',
                    'value' => $this->book->part,
                ]);
        });
});

it('contains a publishers field', function () {
    get(route('books.edit', $this->book))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'publisher',
            ])
                ->containsInput([
                    'name' => 'publisher_name',
                    'id' => 'publisher',
                    'list' => 'publishers',
                    'value' => $this->book->publisher->name,
                ])
                ->containsDatalist([
                    'id' => 'publishers',
                ]);
        });
});

it('contains buttons for adding and removing author inputs', function () {
    get(route('books.edit', $this->book))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsButton([
                'title' => 'Add Author',
            ])
            ->containsButton([
                'title' => 'Remove Author',
            ]);
        });
});

it('contains a submit button', function () {
    get(route('books.edit', $this->book))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'type' => 'submit',
            ]);
        });
});

it('contains an author field for each author', function () {
    $this->book->authors()->attach(Author::factory()->create());
    $this->book->authors()->attach(Author::factory()->create());
    get(route('books.edit', $this->book))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'author',
            ])
                ->containsInput([
                    'id' => 'author',
                ], 1)
                ->containsInput([
                    'name' => 'author[]',
                    'list' => 'authors',
                    'value' => $this->book->authors[0]->last_name.', '.$this->book->authors[0]->first_name,
                ])
                ->containsInput([
                    'name' => 'author[]',
                    'list' => 'authors',
                    'value' => $this->book->authors[1]->last_name.', '.$this->book->authors[1]->first_name,
                ])
                ->containsDatalist([
                    'id' => 'authors',
                ]);
        });
});

it('contains a list of authors', function () {
    Author::factory()
        ->count(2)
        ->sequence(
            [
                'first_name' => 'David',
                'last_name' => 'Eddings',
            ],
            [
                'first_name' => 'Terry',
                'last_name' => 'Goodkind',
            ])->create();

    get(route('books.edit', $this->book))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->findDatalist('#authors', function (AssertDataList $datalist) {
                $datalist->containsOptions(
                    ['value' => 'Eddings, David'],
                    ['value' => 'Goodkind, Terry']
                );
            });
        });
});

it('contains a list of formats', function () {
    Format::factory()
        ->count(2)
        ->sequence(
            [
                'name' => 'Pocket',
                'media_type_id' => $this->mediaTypeId,
            ],
            [
                'name' => 'Hardcover',
                'media_type_id' => $this->mediaTypeId,
            ]
        )
        ->create();

    get(route('books.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->findDatalist('#formats', function (AssertDataList $datalist) {
                $datalist->containsOptions(
                    ['value' => 'Hardcover'],
                    ['value' => 'Pocket']
                );
            });
        });
});

it('contains a list of genres', function () {
    Genre::factory()
        ->count(2)
        ->sequence(
            [
                'name' => 'Fantasy',
                'media_type_id' => $this->mediaTypeId,
            ],
            [
                'name' => 'Crime',
                'media_type_id' => $this->mediaTypeId,
            ]
        )
        ->create();

    get(route('books.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->findDatalist('#genres', function (AssertDataList $datalist) {
                $datalist->containsOptions(
                    ['value' => 'Crime'],
                    ['value' => 'Fantasy']
                );
            });
        });
});

it('contains a list of series', function () {
    Series::factory()
        ->count(2)
        ->sequence(
            ['name' => 'The Wheel Of Time'],
            ['name' => 'The Sword Of Truth']
        )
        ->create();

    get(route('books.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->findDatalist('#series-list', function (AssertDataList $datalist) {
                $datalist->containsOptions(
                    ['value' => 'The Sword Of Truth'],
                    ['value' => 'The Wheel Of Time']
                );
            });
        });
});

it('contains a list of publishers', function () {
    Publisher::factory()
        ->count(2)
        ->sequence(
            ['name' => 'TOR'],
            ['name' => 'Ace Books']
        )
        ->create();

    get(route('books.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->findDatalist('#publishers', function (AssertDataList $datalist) {
                $datalist->containsOptions(
                    ['value' => 'Ace Books'],
                    ['value' => 'TOR']
                );
            });
        });
});
