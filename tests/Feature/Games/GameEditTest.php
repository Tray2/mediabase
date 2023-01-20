<?php

use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Game;
use App\Models\Platform;
use function Pest\Laravel\get;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use Sinnbeck\DomAssertions\Asserts\AssertDatalist;
use Sinnbeck\DomAssertions\Asserts\AssertForm;

uses(FastRefreshDatabase::class);

beforeEach(function () {
    $this->mediaTypeId = MediaType::query()
        ->where('name', 'game')
        ->value('id');
    $this->game = Game::factory()->create();
});

it('can show games.edit view', function () {
    get(route('games.edit', $this->game))
        ->assertOk();
});

it('has a form with the correct post action and method', function () {
    get(route('games.edit', $this->game))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->hasMethod('post')
                ->hasSpoofMethod('put')
                ->hasAction(route('games.update', $this->game))
                ->hasCSRF();
        });
});

it('has a title field', function () {
    get(route('games.edit', $this->game))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'title',
            ])
                ->containsInput([
                    'id' => 'title',
                    'name' => 'title',
                    'value' => $this->game->title,
                ]);
        });
});

it('has a release year field', function () {
    get(route('games.edit', $this->game))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'released_year',
            ])
                ->containsInput([
                    'id' => 'released_year',
                    'name' => 'released_year',
                    'value' => $this->game->released_year,
                ]);
        });
});


it('has a blurb textarea', function () {
    get(route('games.edit', $this->game))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'blurb',
            ])
                ->containsTextarea([
                    'id' => 'blurb',
                    'name' => 'blurb',
                    'value' => $this->game->blurb,
                ]);
        });
});

it('has a format field', function () {
    get(route('games.edit', $this->game))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'format',
            ])
                ->containsInput([
                    'id' => 'format',
                    'name' => 'format_name',
                    'list' => 'formats',
                    'value' => $this->game->format,
                ]);
        });
});

it('has a genres field', function () {
    get(route('games.edit', $this->game))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'genre',
            ])
                ->containsInput([
                    'id' => 'genre',
                    'name' => 'genre_name',
                    'list' => 'genres',
                    'value' => $this->game->genre,
                ]);
        });
});

it('has a platforms field', function () {
    get(route('games.edit', $this->game))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'platform',
            ])
                ->containsInput([
                    'id' => 'platform',
                    'name' => 'platform_name',
                    'list' => 'platforms',
                    'value' => $this->game->platform,
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

    get(route('games.edit', $this->game))
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

    get(route('games.edit', $this->game))
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

it('loads a list of platforms that is sorted in alphabetical order', function () {
    Platform::factory()
        ->count(2)
        ->sequence(
            [
                'name' => 'Pocket',
            ],
            [
                'name' => 'Hardcover',
            ]
        )
        ->create();

    get(route('games.edit', $this->game))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->findDatalist('#platforms', function (AssertDataList $datalist) {
                $datalist->containsOptions(
                    ['value' => 'Hardcover'],
                    ['value' => 'Pocket']
                );
            });
        });
});

it('has a submit button', function () {
    get(route('games.edit', $this->game))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'type' => 'submit',
            ]);
        });
});

it('loads only formats that are game formats', function () {
    $gameFormat = Format::factory()->create([
        'media_type_id' => MediaType::query()
            ->where('name', 'game')
            ->value('id'),
    ]);
    $recordFormat = Format::factory()->create([
        'media_type_id' => MediaType::query()
            ->where('name', 'record')
            ->value('id'),
    ]);

    get(route('games.edit', $this->game))
        ->assertOk()
        ->assertFormExists(fn (AssertForm $form) => $form->findDatalist('#formats', fn (AssertDataList $datalist) => $datalist->containsOptions([
            'value' => $gameFormat->name,
        ])
            ->doesntContainOptions([
                'value' => $recordFormat->name,
            ])
        )
        );
});

it('loads only genres that are game genres', function () {
    $gameGenre = Genre::factory()->create([
        'media_type_id' => MediaType::query()
            ->where('name', 'game')
            ->value('id'),
    ]);
    $recordGenre = Genre::factory()->create([
        'media_type_id' => MediaType::query()
            ->where('name', 'record')
            ->value('id'),
    ]);

    get(route('games.edit', $this->game))
        ->assertFormExists(fn (AssertForm $form) => $form->findDatalist('#genres', fn (AssertDataList $datalist) => $datalist->containsOptions([
            'value' => $gameGenre->name,
        ])
            ->doesntContainOptions([
                'value' => $recordGenre->name,
            ])
        )
        );
});
