<?php

use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Movie;
use App\Models\User;
use Sinnbeck\DomAssertions\Asserts\AssertDatalist;
use Sinnbeck\DomAssertions\Asserts\AssertForm;

beforeEach(function () {
    $this->mediaTypeId = MediaType::query()
        ->where('name', 'movie')
        ->value('id');
    $this->movie = Movie::factory()->create();
    $this->user = User::factory()->create();
});

it('can show movies.edit view', function () {
    actingAs($this->user)->get(route('movies.edit', $this->movie))
        ->assertOk();
});

it('has a form with the correct post action and method', function () {
    actingAs($this->user)->get(route('movies.edit', $this->movie))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->hasMethod('post')
                ->hasSpoofMethod('put')
                ->hasAction(route('movies.update', $this->movie))
                ->hasCSRF();
        });
});

it('has a title field', function () {
    actingAs($this->user)->get(route('movies.edit', $this->movie))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'title',
            ])
                ->containsInput([
                    'id' => 'title',
                    'name' => 'title',
                    'value' => $this->movie->title,
                ]);
        });
});

it('has a release year field', function () {
    actingAs($this->user)->get(route('movies.edit', $this->movie))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'release_year',
            ])
                ->containsInput([
                    'id' => 'release_year',
                    'name' => 'release_year',
                    'value' => $this->movie->release_year,
                ]);
        });
});

it('has a runtime field', function () {
    actingAs($this->user)->get(route('movies.edit', $this->movie))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'runtime',
            ])
                ->containsInput([
                    'id' => 'runtime',
                    'name' => 'runtime',
                    'value' => $this->movie->runtime,
                ]);
        });
});

it('has a release blurb textarea', function () {
    actingAs($this->user)->get(route('movies.edit', $this->movie))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'blurb',
            ])
                ->containsTextarea([
                    'id' => 'blurb',
                    'name' => 'blurb',
                    'value' => $this->movie->blurb,
                ]);
        });
});

it('has a format field', function () {
    actingAs($this->user)->get(route('movies.edit', $this->movie))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'format',
            ])
                ->containsInput([
                    'id' => 'format',
                    'name' => 'format_name',
                    'list' => 'formats',
                    'value' => $this->movie->format->name,
                ]);
        });
});

it('has a genres field', function () {
    actingAs($this->user)->get(route('movies.edit', $this->movie))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'genre',
            ])
                ->containsInput([
                    'id' => 'genre',
                    'name' => 'genre_name',
                    'list' => 'genres',
                    'value' => $this->movie->genre->name,
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

    actingAs($this->user)->get(route('movies.edit', $this->movie))
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

    actingAs($this->user)->get(route('movies.edit', $this->movie))
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
    actingAs($this->user)->get(route('movies.edit', $this->movie))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'type' => 'submit',
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

    actingAs($this->user)->get(route('movies.edit', $this->movie))
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

    actingAs($this->user)->get(route('movies.edit', $this->movie))
        ->assertFormExists(fn (AssertForm $form) => $form->findDatalist('#genres', fn (AssertDataList $datalist) => $datalist->containsOptions([
            'value' => $movieGenre->name,
        ])
            ->doesntContainOptions([
                'value' => $recordGenre->name,
            ])
        )
        );
});
