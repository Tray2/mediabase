<?php

use App\Models\Artist;
use App\Models\Country;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Record;
use App\Models\RecordLabel;
use Carbon\Carbon;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\put;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use Sinnbeck\DomAssertions\Asserts\AssertForm;

uses(FastRefreshDatabase::class);

beforeEach(function () {
    $mediaTypeId = MediaType::query()
        ->where('name', 'record')
        ->value('id');
    $this->artist = Artist::factory()->create();
    $this->genre = Genre::factory()->create(['media_type_id' => $mediaTypeId]);
    $this->format = Format::factory()->create(['media_type_id' => $mediaTypeId]);
    $this->record_label = RecordLabel::factory()->create();
    $this->country = Country::factory()->create();
    $this->record = Record::factory()->create();
    $this->validRecord = array_merge($this->record->toArray(), [
        'artist' => $this->artist->name,
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'country_name' => $this->country->name,
        'record_label_name' => $this->record_label->name,
        'track_positions' => ['01'],
        'track_titles' => ['Some Track'],
        'track_durations' => ['03:50'],
    ]);
    get(route('records.edit', $this->record));
});

it('updates a valid record', function () {
    $validRecord = $this->validRecord;
    $validRecord['title'] = 'Some Updated Title';
    put(route('records.update', $this->record), $validRecord)
        ->assertRedirect(route('records.index'));
    assertDatabaseHas('records', ['title' => 'Some Updated Title']);
    assertDatabaseCount('records', 1);
});

it('redirects and shows an error if the title is missing', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['title'] = '';

    put(route('records.update', $this->record), $invalidRecord)
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('title');
    get(route('records.edit', $this->record))
        ->assertSeeText('The title field is required.');
    assertDatabaseCount('records', 1);
});

it('redirects and shows an error if the barcode is missing', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['barcode'] = '';

    put(route('records.update', $this->record), $invalidRecord)
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('barcode');
    get(route('records.edit', $this->record))
        ->assertSeeText('The barcode field is required.');
    assertDatabaseCount('records', 1);
});

it('redirects and shows an error if the spine code is missing', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['spine_code'] = '';

    put(route('records.update', $this->record), $invalidRecord)
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('spine_code');
    get(route('records.edit', $this->record))
        ->assertSeeText('The spine code field is required.');
    assertDatabaseCount('records', 1);
});

it('redirects and shows an error if the release year is missing', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['release_year'] = '';

    put(route('records.update', $this->record), $invalidRecord)
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('released');
    get(route('records.edit', $this->record))
        ->assertSeeText('The release year field is required.');
    assertDatabaseCount('records', 1);
});

it('shows an error if the release year is not numeric', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['release_year'] = 'Nineteen Eighty Four';

    put(route('records.update', $this->record), $invalidRecord)
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('released');
    get(route('records.edit', $this->record))
        ->assertSeeText('The release year must be a number.');
    assertDatabaseCount('records', 1);
});

it('shows an error if release year is less than four digits', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['release_year'] = 123;

    put(route('records.update', $this->record), $invalidRecord)
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('release_year');
    get(route('records.edit', $this->record))
        ->assertSeeText('The release year must have at least 4 digits.');
    assertDatabaseCount('records', 1);
});

it('shows an error if the release year is more than four digits', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['release_year'] = 12345;

    put(route('records.update', $this->record), $invalidRecord)
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('release_year');
    get(route('records.edit', $this->record))
        ->assertSeeText('The release year must not have more than 4 digits.');
    assertDatabaseCount('records', 1);
});

it('shows an error if the release year is more than a year into the future', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['release_year'] = Carbon::now()->addYear(2)->year;

    put(route('records.update', $this->record), $invalidRecord)
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('release_year');
    get(route('records.edit', $this->record))
        ->assertSeeText('The release year must be between 1800 and '.Carbon::now()->addYear(1)->year.'.');
    assertDatabaseCount('records', 1);
});

it('shows an error if the artist is missing', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['artist'] = '';

    put(route('records.update', $this->record), $invalidRecord)
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('artist');
    get(route('records.edit', $this->record))
        ->assertSeeText('The artist field is required.');

    assertDatabaseCount('records', 1);
});

it('creates a new artist if the one passed does not exist in the database', function () {
    $validRecord = $this->validRecord;
    $validRecord['artist'] = 'Public Enemy';

    put(route('records.update', $this->record), $validRecord)
        ->assertRedirect(route('records.index'));
    assertDatabaseCount('records', 1);
});

it('shows an error if the genre is missing', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['genre_name'] = '';

    put(route('records.update', $this->record), $invalidRecord)
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('genre_name');
    get(route('records.edit', $this->record))
        ->assertSeeText('The genre name field is required.');
    assertDatabaseCount('records', 1);
});

it('creates a new genre if the one passed does not exist in the database', function () {
    $validRecord = $this->validRecord;
    $validRecord['genre_name'] = 'Fantasy';

    put(route('records.update', $this->record), $validRecord)
        ->assertRedirect(route('records.index'));
    assertDatabaseCount('records', 1);
    assertDatabaseHas('genres', ['name' => 'Fantasy']);
});

it('shows an error if the country is missing', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['country_name'] = '';

    put(route('records.update', $this->record), $invalidRecord)
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('country_name');
    get(route('records.edit', $this->record))
        ->assertSeeText('The country name field is required.');
    assertDatabaseCount('records', 1);
});

it('creates a new country if the one passed does not exist in the database', function () {
    $validRecord = $this->validRecord;
    $validRecord['country_name'] = 'Fantasy';

    put(route('records.update', $this->record), $validRecord)
        ->assertRedirect(route('records.index'));
    assertDatabaseCount('records', 1);
    assertDatabaseHas('countries', ['name' => 'Fantasy']);
});

it('shows an error if the format is missing', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['format_name'] = '';

    put(route('records.update', $this->record), $invalidRecord)
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('format_name');
    get(route('records.edit', $this->record))
        ->assertSeeText('The format name field is required.');
    assertDatabaseCount('records', 1);
});

it('creates a new format if the one passed does not exist in the database', function () {
    $validRecord = $this->validRecord;
    $validRecord['format_name'] = 'Hardcover';

    put(route('records.update', $this->record), $validRecord)
        ->assertRedirect(route('records.index'));
    assertDatabaseCount('records', 1);
    assertDatabaseHas('formats', ['name' => 'Hardcover']);
});

it('shows an error if the record label name is missing', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['record_label_name'] = '';

    put(route('records.update', $this->record), $invalidRecord)
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('record_label_name');
    get(route('records.edit', $this->record))
        ->assertSeeText('The record label name field is required.');
    assertDatabaseCount('records', 1);
});

it('creates a new record label if the one passed does not exist in the database', function () {
    $validRecord = $this->validRecord;
    $validRecord['record_label_name'] = 'TOR';

    put(route('records.update', $this->record), $validRecord)
        ->assertRedirect(route('records.index'));
    assertDatabaseCount('records', 1);
    assertDatabaseHas('record_labels', ['name' => 'TOR']);
});

it('has the old values in the form if the validation fails', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['title'] = '';

    put(route('records.update', $this->record), $invalidRecord)
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('title');
    get(route('records.edit', $this->record))
        ->assertSeeText('The title field is required.')
        ->assertOk()
        ->assertSeeText('The title field is required.')
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'name' => 'release_year',
                'value' => $this->validRecord['release_year'],
            ])
                ->containsInput([
                    'name' => 'artist',
                    'value' => $this->validRecord['artist'],
                ])
                ->containsInput([
                    'name' => 'format_name',
                    'value' => $this->validRecord['format_name'],
                ])
                ->containsInput([
                    'name' => 'genre_name',
                    'value' => $this->validRecord['genre_name'],
                ])
                ->containsInput([
                    'name' => 'record_label_name',
                    'value' => $this->validRecord['record_label_name'],
                ]);
        });
});

it('has the old title value in the form if the validation fails', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['release_year'] = '';

    put(route('records.update', $this->record), $invalidRecord)
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('title');
    get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'name' => 'title',
                'value' => $this->validRecord['title'],
            ]);
        });
});
