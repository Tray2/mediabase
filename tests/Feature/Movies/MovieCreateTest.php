<?php

use App\Models\Actor;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use function Pest\Laravel\get;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use Sinnbeck\DomAssertions\Asserts\AssertDatalist;
use Sinnbeck\DomAssertions\Asserts\AssertForm;

uses(FastRefreshDatabase::class);

beforeEach(function () {
    $this->mediaTypeId = MediaType::query()
        ->where('name', 'movie')
        ->value('id');
});

it('can show movies.create view', function () {
    get(route('movies.create'))
        ->assertOk();
});

it('has a form with the correct post action and method', function () {
    get(route('movies.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->hasMethod('post')
                ->hasAction(route('movies.store'))
                ->hasCSRF();
        });
});

it('has a title field', function () {
    get(route('movies.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'title',
            ])
                ->containsInput([
                    'id' => 'title',
                    'name' => 'title',
                ]);
        });
});

it('has a release year field', function () {
    get(route('movies.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'release_year',
            ])
                ->containsInput([
                    'id' => 'release_year',
                    'name' => 'release_year',
                ]);
        });
});

it('has a runtime field', function () {
    get(route('movies.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'runtime',
            ])
                ->containsInput([
                    'id' => 'runtime',
                    'name' => 'runtime',
                ]);
        });
});

it('has a release blurb textarea', function () {
    get(route('movies.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'blurb',
            ])
                ->containsTextarea([
                    'id' => 'blurb',
                    'name' => 'blurb',
                ]);
        });
});

it('has an actor field', function () {
    get(route('movies.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'actor',
            ])
                ->containsInput([
                    'name' => 'actor[]',
                    'id' => 'actor',
                    'list' => 'actors',
                ])
                ->containsDatalist([
                    'id' => 'actors',
                ]);
        });
});

it('has a format field', function () {
    get(route('movies.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'format',
            ])
                ->containsInput([
                    'id' => 'format',
                    'name' => 'format_name',
                    'list' => 'formats',
                ]);
        });
});

it('has a genres field', function () {
    get(route('movies.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'genre',
            ])
                ->containsInput([
                    'id' => 'genre',
                    'name' => 'genre_name',
                    'list' => 'genres',
                ]);
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

    get(route('movies.create'))
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

it('loads a list of actors that is sorted in alphabetical order', function () {
    Actor::factory()
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

    get(route('movies.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->findDatalist('#actors', function (AssertDataList $datalist) {
                $datalist->containsOptions(
                    ['value' => 'David Eddings'],
                    ['value' => 'Terry Goodkind']
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

    get(route('movies.create'))
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

it('has a submit button', function () {
    get(route('movies.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'type' => 'submit',
            ]);
        });
});

it('has a add actor button', function () {
    get(route('movies.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsButton([
                'title' => 'Add Actor',
            ]);
        });
});

it('loads only formats that are movie formats', function () {
    $movieFormat = Format::factory()->create([
        'media_type_id' => MediaType::query()
            ->where('name', 'movie')
            ->value('id'),
    ]);
    $recordFormat = Format::factory()->create([
        'media_type_id' => MediaType::query()
            ->where('name', 'record')
            ->value('id'),
    ]);

    get(route('movies.create'))
        ->assertOk()
        ->assertFormExists(fn (AssertForm $form) => $form->findDatalist('#formats', fn (AssertDataList $datalist) => $datalist->containsOptions([
            'value' => $movieFormat->name,
        ])
            ->doesntContainOptions([
                'value' => $recordFormat->name,
            ])
        )
        );
});

it('loads only genres that are movie genres', function () {
    $movieGenre = Genre::factory()->create([
        'media_type_id' => MediaType::query()
            ->where('name', 'movie')
            ->value('id'),
    ]);
    $recordGenre = Genre::factory()->create([
        'media_type_id' => MediaType::query()
            ->where('name', 'record')
            ->value('id'),
    ]);

    get(route('movies.create'))
        ->assertFormExists(fn (AssertForm $form) => $form->findDatalist('#genres', fn (AssertDataList $datalist) => $datalist->containsOptions([
            'value' => $movieGenre->name,
        ])
            ->doesntContainOptions([
                'value' => $recordGenre->name,
            ])
        )
        );
});
