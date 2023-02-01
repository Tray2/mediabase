<?php

use App\Models\Author;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Publisher;
use App\Models\Series;
use function Pest\Laravel\get;
use Sinnbeck\DomAssertions\Asserts\AssertDatalist;
use Sinnbeck\DomAssertions\Asserts\AssertForm;


beforeEach(function () {
    $this->mediaTypeId = MediaType::query()
        ->where('name', 'book')
        ->value('id');
});

it('can show books.create page', function () {
    get(route('books.create'))
        ->assertOk();
});

it('has a form with the correct post action and method', function () {
    get(route('books.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->hasMethod('post')
                ->hasAction(route('books.store'))
                ->hasCSRF();
        });
});

it('has a title field', function () {
    get(route('books.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'title',
            ])
                ->containsInput([
                    'name' => 'title',
                    'id' => 'title',
                ]);
        });
});

it('has a published_year field', function () {
    get(route('books.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'published_year',
            ])
                ->containsInput([
                    'name' => 'published_year',
                    'id' => 'published_year',
                ]);
        });
});

it('has an author field', function () {
    get(route('books.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'author',
            ])
                ->containsInput([
                    'name' => 'author[]',
                    'id' => 'author',
                    'list' => 'authors',
                ])
                ->containsDatalist([
                    'id' => 'authors',
                ]);
        });
});

it('has a format field', function () {
    get(route('books.create'))
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'format',
            ])
                ->containsInput([
                    'name' => 'format_name',
                    'id' => 'format',
                    'list' => 'formats',
                ])
                ->containsDatalist([
                    'id' => 'formats',
                ]);
        });
});

it('has a genres field', function () {
    get(route('books.create'))
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'genre',
            ])
                ->containsInput([
                    'name' => 'genre_name',
                    'id' => 'genre',
                    'list' => 'genres',
                ])
                ->containsDatalist([
                    'id' => 'genres',
                ]);
        });
});

it('has an isbn field', function () {
    get(route('books.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'isbn',
            ])
                ->containsInput([
                    'id' => 'isbn',
                    'name' => 'isbn',
                ]);
        });
});

it('has a blurb text area', function () {
    get(route('books.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'blurb',
            ])
                ->containsTextArea([
                    'id' => 'blurb',
                    'name' => 'blurb',
                ]);
        });
});

it('has a series field', function () {
    get(route('books.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'series',
            ])
                ->containsInput([
                    'id' => 'series',
                    'name' => 'series_name',
                    'list' => 'series-list',
                ])
                ->containsDatalist([
                    'id' => 'series-list',
                ]);
        });
});

it('has a part field', function () {
    get(route('books.create'))
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'part',
            ])
                ->containsInput([
                    'name' => 'part',
                    'id' => 'part',
                ]);
        });
});

it('has a publishers field', function () {
    get(route('books.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'publisher',
            ])
                ->containsInput([
                    'id' => 'publisher',
                    'name' => 'publisher_name',
                    'list' => 'publishers',
                ])
                ->containsDatalist([
                    'id' => 'publishers',
                ]);
        });
});

it('loads a list of authors that is sorted in alphabetical order', function () {
    Author::factory()
        ->count(2)
        ->sequence(
            [
                'last_name' => 'Goodkind',
                'first_name' => 'Terry',
            ],
            [
                'last_name' => 'Eddings',
                'first_name' => 'David',
            ]
        )
        ->create();

    get(route('books.create'))
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

it('loads a list of formats that is sorted in alphabetical order', function () {
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

it('loads a list of genres that is sorted in alphabetical order', function () {
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

it('loads a list of series that is sorted in alphabetical order', function () {
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

it('loads a list of publishers that is sorted in alphabetical order', function () {
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

it('has a submit button', function () {
    get(route('books.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'type' => 'submit',
            ]);
        });
});

it('has a add author button', function () {
    get(route('books.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsButton([
                'title' => 'Add Author',
            ]);
        });
});

it('loads only formats that are book formats', function () {
    $bookFormat = Format::factory()->create([
        'media_type_id' => MediaType::query()
            ->where('name', 'book')
            ->value('id'),
    ]);
    $recordFormat = Format::factory()->create([
        'media_type_id' => MediaType::query()
            ->where('name', 'record')
            ->value('id'),
    ]);

    get(route('books.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) use ($bookFormat, $recordFormat) {
            $form->findDatalist('#formats', function (AssertDatalist $datalist) use ($bookFormat, $recordFormat) {
                $datalist->doesntContainOption([
                    'value' => $recordFormat->name,
                ])
                    ->containsOptions([
                        'value' => $bookFormat->name,
                    ]);
            });
        });
});

it('loads only genres that are book genres', function () {
    $bookGenre = Genre::factory()->create([
        'media_type_id' => MediaType::query()
            ->where('name', 'book')
            ->value('id'),
    ]);
    $recordGenre = Genre::factory()->create([
        'media_type_id' => MediaType::query()
            ->where('name', 'record')
            ->value('id'),
    ]);

    get(route('books.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) use ($bookGenre, $recordGenre) {
            $form->findDatalist('#genres', function (AssertDatalist $datalist) use ($bookGenre, $recordGenre) {
                $datalist->doesntContainOption([
                    'value' => $recordGenre->name,
                ])
                    ->containsOptions([
                        'value' => $bookGenre->name,
                    ]);
            });
        });
});

it('loads only genres that are book genres arrow', function () {
    $bookGenre = Genre::factory()->create([
        'media_type_id' => MediaType::query()
            ->where('name', 'book')
            ->value('id'),
    ]);
    $recordGenre = Genre::factory()->create([
        'media_type_id' => MediaType::query()
            ->where('name', 'record')
            ->value('id'),
    ]);

    get(route('books.create'))
        ->assertOk()
        ->assertFormExists(fn (AssertForm $form) => $form->findDatalist('#genres', fn (AssertDatalist $datalist) => $datalist->doesntContainOption([
            'value' => $recordGenre->name,
        ])
                    ->containsOptions([
                        'value' => $bookGenre->name,
                    ])
            )
        );
});
