<?php

use App\Models\Artist;
use App\Models\Country;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\RecordLabel;
use Carbon\Carbon;
use Database\Seeders\MediaTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(MediaTypeSeeder::class);
    $mediaTypeId = MediaType::query()
        ->where('name', 'record')
        ->value('id');
    $this->artist = Artist::factory()->create();
    $this->genre = Genre::factory()->create(['media_type_id' => $mediaTypeId]);
    $this->format = Format::factory()->create(['media_type_id' => $mediaTypeId]);
    $this->record_label = RecordLabel::factory()->create();
    $this->country = Country::factory()->create();
    $this->validRecord = [
        'title' => 'Some Title',
        'release_year' => 1984,
        'barcode' => '9781398510784',
        'spine_code' => '11122554',
        'artist' => $this->artist->name,
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'country_name' => $this->country->name,
        'record_label_name' => $this->record_label->name,
    ];
    get(route('records.create'));
});

it('stores a valid record', function () {
    post(route('records.store', $this->validRecord))
        ->assertRedirect(route('records.index'));
    assertDatabaseCount('records', 1);
});

it('redirects and shows an error if the title is missing', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['title'] = '';

    post(route('records.store', $invalidRecord))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('title');
    get(route('records.create'))
        ->assertSeeText('The title field is required.');
    assertDatabaseCount('records', 0);
});

it('redirects and shows an error if the barcode is missing', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['barcode'] = '';

    post(route('records.store', $invalidRecord))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('barcode');
    get(route('records.create'))
        ->assertSeeText('The barcode field is required.');
    assertDatabaseCount('records', 0);
});

it('redirects and shows an error if the spine code is missing', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['spine_code'] = '';

    post(route('records.store', $invalidRecord))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('spine_code');
    get(route('records.create'))
        ->assertSeeText('The spine code field is required.');
    assertDatabaseCount('records', 0);
});

it('redirects and shows an error if the release year is missing', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['release_year'] = '';

    post(route('records.store', $invalidRecord))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('released');
    get(route('records.create'))
        ->assertSeeText('The release year field is required.');
    assertDatabaseCount('records', 0);
});

it('shows an error if the release year is not numeric', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['release_year'] = 'Nineteen Eighty Four';

    post(route('records.store', $invalidRecord))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('released');
    get(route('records.create'))
        ->assertSeeText('The release year must be a number.');
    assertDatabaseCount('records', 0);
});

it('shows an error if release year is less than four digits', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['release_year'] = 123;

    post(route('records.store', $invalidRecord))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('release_year');
    get(route('records.create'))
        ->assertSeeText('The release year must have at least 4 digits.');
    assertDatabaseCount('records', 0);
});

it('shows an error if the release year is more than four digits', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['release_year'] = 12345;

    post(route('records.store', $invalidRecord))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('release_year');
    get(route('records.create'))
        ->assertSeeText('The release year must not have more than 4 digits.');
    assertDatabaseCount('records', 0);
});

it('shows an error if the release year is more than a year into the future', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['release_year'] = Carbon::now()->addYear(2)->year;

    post(route('records.store', $invalidRecord))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('release_year');
    get(route('records.create'))
        ->assertSeeText('The release year must be between 1800 and '.Carbon::now()->addYear(1)->year.'.');
    assertDatabaseCount('records', 0);
});

it('shows an error if the artist is missing', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['artist'] = '';

    post(route('records.store', $invalidRecord))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('artist');
    get(route('records.create'))
        ->assertSeeText('The artist field is required.');

    assertDatabaseCount('records', 0);
});

it('creates a new artist if the one passed does not exist in the database', function () {
    $validRecord = $this->validRecord;
    $validRecord['artist'] = 'Public Enemy';

    post(route('records.store', $validRecord))
        ->assertRedirect(route('records.index'));
    assertDatabaseCount('records', 1);
});

it('shows an error if the genre is missing', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['genre_name'] = '';

    post(route('records.store', $invalidRecord))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('genre_name');
    get(route('records.create'))
        ->assertSeeText('The genre name field is required.');
    assertDatabaseCount('records', 0);
});

it('creates a new genre if the one passed does not exist in the database', function () {
    $validRecord = $this->validRecord;
    $validRecord['genre_name'] = 'Fantasy';

    post(route('records.store', $validRecord))
        ->assertRedirect(route('records.index'));
    assertDatabaseCount('records', 1);
    assertDatabaseHas('genres', ['name' => 'Fantasy']);
});

it('shows an error if the country is missing', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['country_name'] = '';

    post(route('records.store', $invalidRecord))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('country_name');
    get(route('records.create'))
        ->assertSeeText('The country name field is required.');
    assertDatabaseCount('records', 0);
});

it('creates a new country if the one passed does not exist in the database', function () {
    $validRecord = $this->validRecord;
    $validRecord['country_name'] = 'Fantasy';

    post(route('records.store', $validRecord))
        ->assertRedirect(route('records.index'));
    assertDatabaseCount('records', 1);
    assertDatabaseHas('countries', ['name' => 'Fantasy']);
});

it('shows an error if the format is missing', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['format_name'] = '';

    post(route('records.store', $invalidRecord))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('format_name');
    get(route('records.create'))
        ->assertSeeText('The format name field is required.');
    assertDatabaseCount('records', 0);
});

it('creates a new format if the one passed does not exist in the database', function () {
    $validRecord = $this->validRecord;
    $validRecord['format_name'] = 'Hardcover';

    post(route('records.store', $validRecord))
        ->assertRedirect(route('records.index'));
    assertDatabaseCount('records', 1);
    assertDatabaseHas('formats', ['name' => 'Hardcover']);
});

it('shows an error if the record label name is missing', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['record_label_name'] = '';

    post(route('records.store', $invalidRecord))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('record_label_name');
    get(route('records.create'))
        ->assertSeeText('The record label name field is required.');
    assertDatabaseCount('records', 0);
});

it('creates a new record label if the one passed does not exist in the database', function () {
    $validRecord = $this->validRecord;
    $validRecord['record_label_name'] = 'TOR';

    post(route('records.store', $validRecord))
        ->assertRedirect(route('records.index'));
    assertDatabaseCount('records', 1);
    assertDatabaseHas('record_labels', ['name' => 'TOR']);
});

it('has the old values in the form if the validation fails', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['title'] = '';
    $formatPattern = '/<input(.)*value="'.$invalidRecord['format_name'].'"(.)*>/';
    $genrePattern = '/<input(.)*value="'.$invalidRecord['genre_name'].'"(.)*>/';
    $recordLabelPattern = '/<input(.)*value="'.$invalidRecord['record_label_name'].'"(.)*>/';
    $artistPattern = '/<input(.)*value="'.$invalidRecord['artist'].'"(.)*>/';

    post(route('records.store', $invalidRecord))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('title');
    $response = get(route('records.create'))
        ->assertSeeText('The title field is required.')
        ->assertSee([
            'value="'.$this->validRecord['release_year'],
        ], false);

    $this->assertMatchesRegularExpression($artistPattern, $response->content());
    $this->assertMatchesRegularExpression($formatPattern, $response->content());
    $this->assertMatchesRegularExpression($genrePattern, $response->content());
    $this->assertMatchesRegularExpression($recordLabelPattern, $response->content());
});

it('has the old title value in the form if the validation fails', function () {
    $invalidRecord = $this->validRecord;
    $invalidRecord['release_year'] = '';

    post(route('records.store', $invalidRecord))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('title');
    get(route('records.create'))
        ->assertSee([
            'value="'.$this->validRecord['title'],
        ], false);
});
