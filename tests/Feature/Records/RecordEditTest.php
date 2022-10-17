<?php

use App\Models\Artist;
use App\Models\Country;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Record;
use App\Models\RecordLabel;
use App\Models\Track;
use Database\Seeders\MediaTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(MediaTypeSeeder::class);
    $this->mediaTypeId = MediaType::query()
        ->where('name', 'record')
        ->value('id');
    $this->record = Record::factory()->create();
    $this->track = Track::factory()->create([
        'record_id' => $this->record->id
    ]);
});

it('can show records.edit page', function () {
    get(route('records.edit', $this->record))
        ->assertOk();
});

it('has a form with the correct post action and method', function () {
    get(route('records.edit', $this->record))
        ->assertSee([
            'method="post"',
            'action="'.route('records.update', $this->record).'"',
        ], false);
});

it('has a token field', function () {
    get(route('records.create'))
        ->assertSee([
            'name="_token"',
        ], false);
});

it('has a method field with the action put', function () {
    get(route('records.edit', $this->record))
        ->assertSee([
            'name="_method"',
            'value="PUT"',
        ], false);
});

it('has a title field', function () {
    get(route('records.create'))
        ->assertSee([
            'for="title"',
            'id="title"',
            'name="title"',
        ], false);
});

it('has a barcode field', function () {
    get(route('records.create'))
        ->assertSee([
            'for="barcode"',
            'id="barcode"',
            'name="barcode"',
        ], false);
});

it('has a spine_code field', function () {
    get(route('records.create'))
        ->assertSee([
            'for="spine_code"',
            'id="spine_code"',
            'name="spine_code"',
        ], false);
});

it('has a country_name field', function () {
    get(route('records.create'))
        ->assertSee([
            'for="country_name',
            'id="country_name"',
            'name="country_name"',
            'list="countries',
            'datalist id="countries',
        ], false);
});

it('has a release year field', function () {
    get(route('records.create'))
        ->assertSee([
            'for="release_year',
            'id="release_year"',
            'name="release_year"',
        ], false);
});

it('has an artist field', function () {
    get(route('records.create'))
        ->assertSee([
            'for="artist',
            'id="artist"',
            'list="artists',
            'datalist id="artists',
        ], false);
});

it('has a format field', function () {
    get(route('records.create'))
        ->assertSee([
            'for="format',
            'id="format"',
            'name="format_name"',
            'list="formats',
            'datalist id="formats',
        ], false);
});

it('has a genres field', function () {
    get(route('records.create'))
        ->assertSee([
            'for="genre',
            'id="genre"',
            'name="genre_name"',
            'list="genres',
            'datalist id="genres',
        ], false);
});

it('has a record label field', function () {
    get(route('records.create'))
        ->assertSee([
            'for="record_label',
            'id="record_label"',
            'name="record_label_name"',
            'list="record_labels',
            'datalist id="record_labels',
        ], false);
});

it('loads a list of artists that is sorted in alphabetical order', function () {
    Artist::factory()
        ->count(2)
        ->sequence(
            ['name' => 'Public Enemy'],
            ['name' => 'Anthrax']
        )
        ->create();

    get(route('records.create'))
        ->assertOk()
        ->assertSeeInOrder([
            'Anthrax',
            'Public Enemy',
        ]);
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

    get(route('records.create'))
        ->assertOk()
        ->assertSeeInOrder([
            'Hardcover',
            'Pocket',
        ]);
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

    get(route('records.create'))
        ->assertOk()
        ->assertSeeInOrder([
            'Crime',
            'Fantasy',
        ]);
});

it('loads a list of record labels that is sorted in alphabetical order', function () {
    RecordLabel::factory()
        ->count(2)
        ->sequence(
            ['name' => 'TOR'],
            ['name' => 'Ace Books']
        )
        ->create();

    get(route('records.create'))
        ->assertOk()
        ->assertSeeInOrder([
            'Ace Books',
            'TOR',
        ]);
});

it('has a submit button', function () {
    get(route('records.create'))
        ->assertSee([
            '<input type="submit">',
        ], false);
});

it('loads a list of countries that is sorted in alphabetical order', function () {
    Country::factory()
        ->count(2)
        ->sequence(
            ['name' => 'Sweden'],
            ['name' => 'England']
        )
        ->create();

    get(route('records.create'))
        ->assertOk()
        ->assertSeeInOrder([
            'England',
            'Sweden',
        ]);
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

    get(route('records.create'))
        ->assertSee('value="'.$recordFormat->name.'"', false)
        ->assertDontSee('value="'.$bookFormat->name.'"', false);
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

    get(route('records.create'))
        ->assertSee('value="'.$recordGenre->name.'"', false)
        ->assertDontSee('value="'.$bookGenre->name.'"', false);
});

it('has the title of the record in the title field', function () {
    get(route('records.edit', $this->record))
        ->assertSee([$this->record->title]);
});

it('has the release year int the release year field', function () {
    get(route('records.edit', $this->record))
        ->assertSee([$this->record->release_year]);
});

it('has the barcode in the barcode field', function () {
    get(route('records.edit', $this->record))
        ->assertSee([$this->record->barcode]);
});

it('has the spine code in the spine code field', function () {
    get(route('records.edit', $this->record))
        ->assertSee([$this->record->spine_code]);
});

it('has the format of the record in the format field', function () {
    $pattern = '/<input(.)*value="'.$this->record->format->name.'"(.)*>/';
    $response = get(route('records.edit', $this->record))
        ->assertSee([
            'value="'.$this->record->format->name.'"',
        ], false);
    $this->assertMatchesRegularExpression($pattern, $response->content());
});

it('has the genre of the records in the genre field', function () {
    $pattern = '/<input(.)*value="'.$this->record->genre->name.'"(.)*>/';
    $response = get(route('records.edit', $this->record))
        ->assertSee([
            'value="'.$this->record->genre->name.'"',
        ], false);
    $this->assertMatchesRegularExpression($pattern, $response->content());
});

it('has the record label of the record in the record label field', function () {
    $pattern = '/<input(.)*value="'.$this->record->recordLabel->name.'"(.)*>/';
    $response = get(route('records.edit', $this->record))
        ->assertSee([
            'value="'.$this->record->recordLabel->name.'"',
        ], false);
    $this->assertMatchesRegularExpression($pattern, $response->content());
});

it('has the artist of the record in the artist field', function () {
    $pattern = '/<input(.)*value="'.$this->record->artist->name.'"(.)*>/';
    $response = get(route('records.edit', $this->record))
        ->assertSee([
            'value="'.$this->record->artist->name.'"',
        ], false);
    $this->assertMatchesRegularExpression($pattern, $response->content());
});

it('has the country of the record in the country field', function () {
    $pattern = '/<input(.)*value="'.$this->record->country->name.'"(.)*>/';
    $response = get(route('records.edit', $this->record))
        ->assertSee([
            'value="'.$this->record->country->name.'"',
        ], false);
    $this->assertMatchesRegularExpression($pattern, $response->content());
});
