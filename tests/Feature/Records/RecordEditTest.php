<?php

use App\Models\Artist;
use App\Models\Country;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Record;
use App\Models\RecordLabel;
use App\Models\Track;
use App\Models\User;
use Sinnbeck\DomAssertions\Asserts\AssertDatalist;
use Sinnbeck\DomAssertions\Asserts\AssertForm;

beforeEach(function () {
    $this->mediaTypeId = MediaType::query()
        ->where('name', 'record')
        ->value('id');
    $this->record = Record::factory()->create();
    $this->track = Track::factory()->create([
        'record_id' => $this->record->id,
    ]);
    $this->user = User::factory()->create();
});

it('can show records.edit page', function () {
    actingAs($this->user)->get(route('records.edit', $this->record))
        ->assertOk();
});

it('has a form with the correct post action and method', function () {
    actingAs($this->user)->get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->hasMethod('post')
                ->hasCSRF()
                ->hasSpoofMethod('put')
                ->hasAction(route('records.update', $this->record));
        });
});

it('has a title field', function () {
    actingAs($this->user)->get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'title',
            ])
                ->containsInput([
                    'name' => 'title',
                    'id' => 'title',
                    'value' => $this->record->title,
                ]);
        });
});

it('has a barcode field', function () {
    actingAs($this->user)->get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'barcode',
            ])
                ->containsInput([
                    'id' => 'barcode',
                    'name' => 'barcode',
                    'value' => $this->record->barcode,
                ]);
        });
});

it('has a spine_code field', function () {
    actingAs($this->user)->get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'spine_code',
            ])
                ->containsInput([
                    'id' => 'spine_code',
                    'name' => 'spine_code',
                    'value' => $this->record->spine_code,
                ]);
        });
});

it('has a country_name field', function () {
    actingAs($this->user)->get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'country_name',
            ])
                ->containsInput([
                    'id' => 'country_name',
                    'name' => 'country_name',
                    'list' => 'countries',
                    'value' => $this->record->country_name,
                ]);
        });
});

it('has a release year field', function () {
    actingAs($this->user)->get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'release_year',
            ])
                ->containsInput([
                    'id' => 'release_year',
                    'name' => 'release_year',
                    'value' => $this->record->release_year,
                ]);
        });
});

it('has an artist field', function () {
    actingAs($this->user)->get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'artist',
            ])
                ->containsInput([
                    'id' => 'artist',
                    'name' => 'artist',
                    'list' => 'artists',
                    'value' => $this->record->artist->name,
                ]);
        });
});

it('has a format field', function () {
    actingAs($this->user)->get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'format',
            ])
                ->containsInput([
                    'id' => 'format',
                    'name' => 'format_name',
                    'list' => 'formats',
                    'value' => $this->record->format->name,
                ]);
        });
});

it('has a genres field', function () {
    actingAs($this->user)->get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'genre',
            ])
                ->containsInput([
                    'id' => 'genre',
                    'name' => 'genre_name',
                    'list' => 'genres',
                    'value' => $this->record->genre->name,
                ]);
        });
});

it('has a record label field', function () {
    actingAs($this->user)->get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'record_label',
            ])
                ->containsInput([
                    'id' => 'record_label',
                    'name' => 'record_label_name',
                    'list' => 'record_labels',
                    'value' => $this->record->recordLabel->name,
                ]);
        });
});

it('loads a list of artists that is sorted in alphabetical order', function () {
    Artist::factory()
        ->count(2)
        ->sequence(
            ['name' => 'Public Enemy'],
            ['name' => 'Anthrax']
        )
        ->create();

    actingAs($this->user)->get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->findDatalist('#artists', function (AssertDataList $datalist) {
                $datalist->containsOptions(
                    ['value' => 'Anthrax'],
                    ['value' => 'Public Enemy']
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

    actingAs($this->user)->get(route('records.edit', $this->record))
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

    actingAs($this->user)->get(route('records.edit', $this->record))
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

it('loads a list of record labels that is sorted in alphabetical order', function () {
    RecordLabel::factory()
        ->count(2)
        ->sequence(
            ['name' => 'TOR'],
            ['name' => 'Ace Books']
        )
        ->create();

    actingAs($this->user)->get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->findDatalist('#record_labels', function (AssertDataList $datalist) {
                $datalist->containsOptions(
                    ['value' => 'Ace Books'],
                    ['value' => 'TOR']
                );
            });
        });
});

it('has a submit button', function () {
    actingAs($this->user)->get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'type' => 'submit',
            ]);
        });
});

it('loads a list of countries that is sorted in alphabetical order', function () {
    Country::factory()
        ->count(2)
        ->sequence(
            ['name' => 'Sweden'],
            ['name' => 'England']
        )
        ->create();

    actingAs($this->user)->get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->findDatalist('#countries', function (AssertDataList $datalist) {
                $datalist->containsOptions(
                    ['value' => 'England'],
                    ['value' => 'Sweden']
                );
            });
        });
});

it('loads only formats that are record formats', function () {
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

    actingAs($this->user)->get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(fn (AssertForm $form) => $form->findDatalist('#formats', fn (AssertDataList $datalist) => $datalist->containsOptions(
            ['value' => $recordFormat->name],
        )
                    ->doesntContainsOptions(
                        ['value' => $bookFormat->name]
                    )
        )
        );
});

it('loads only genres that are record genres', function () {
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

    actingAs($this->user)->get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(fn (AssertForm $form) => $form->findDatalist('#genres', fn (AssertDataList $datalist) => $datalist->containsOptions(
            ['value' => $recordGenre->name],
        )
            ->doesntContainsOptions(
                ['value' => $bookGenre->name]
            )
        )
        );
});
