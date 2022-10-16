<?php

use App\Models\Artist;
use App\Models\Country;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\RecordLabel;
use Database\Seeders\MediaTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

beforeEach(function() {
    $this->seed(MediaTypeSeeder::class);
    $mediaTypeId = MediaType::query()
        ->where('name', 'record')
        ->value('id');
    $this->artist = Artist::factory()->create();
    $this->genre = Genre::factory()->create(['media_type_id' => $mediaTypeId]);
    $this->format = Format::factory()->create(['media_type_id' => $mediaTypeId]);
    $this->country = Country::factory()->create();
    $this->record_label = RecordLabel::factory()->create();
    $this->record = [
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

    $this->validTrack = [
      'track_positions' => ['01'],
      'track_titles' => ['Some Track Title'],
      'track_durations' => ['03:20'],
      'track_mixes' => ['Some Mix'],
    ];
    get(route('records.create'));
});

it('stores a valid track without mix', function () {
    $validTrack = $this->validTrack;
    $validTrack['track_mixes'][0] = '';
    post(route('records.store', array_merge($this->record, $validTrack)))
        ->assertRedirect(route('records.index'))
        ->assertSessionDoesntHaveErrors();
        assertDatabaseCount('records', 1);
        assertDatabaseCount('tracks', 1);
});

it('stores a valid track with mix', function () {
    $validTrack = $this->validTrack;
    post(route('records.store', array_merge($this->record, $validTrack)))
        ->assertRedirect(route('records.index'))
        ->assertSessionDoesntHaveErrors();
    assertDatabaseCount('records', 1);
    assertDatabaseCount('tracks', 1);
});

it('stores a valid track with for a various artist record', function () {
    $validTrack = $this->validTrack;
    $validRecord = $this->record;
    $validRecord['artist'] = 'Various Artists';
    $validTrack['track_artists'] = ['Some Artist'];
    post(route('records.store', array_merge($validRecord, $validTrack)))
        ->assertRedirect(route('records.index'))
        ->assertSessionDoesntHaveErrors();
    assertDatabaseCount('records', 1);
    assertDatabaseMissing('tracks', ['artist_id' => null]);
});

it('stores multiple valid tracks', function () {
    $validTrack = $this->validTrack;
    $validTrack['track_positions'][] = '02';
    $validTrack['track_titles'][] = 'Another Track';
    $validTrack['track_durations'][] = '03:50';
    $validTrack['track_mixes'][] = '';
    $validRecord = $this->record;

    post(route('records.store', array_merge($validRecord, $validTrack)))
        ->assertRedirect(route('records.index'))
        ->assertSessionDoesntHaveErrors();
    assertDatabaseCount('records', 1);
    assertDatabaseCount('tracks', 2);
});

it('redirects and shows an error if the track position is not an array', function () {
    $invalidTrack = $this->validTrack;
    $invalidTrack['track_positions'] = '';
    post(route('records.store', array_merge($this->record, $invalidTrack)))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('track_positions');
    assertDatabaseCount('records', 0);
    assertDatabaseCount('tracks', 0);
    get(route('records.create'))
        ->assertSee('The track positions field is required.');
});

it('redirects and shows an error if the track position is an empty array', function () {
    $invalidTrack = $this->validTrack;
    $invalidTrack['track_positions'] = [];
    post(route('records.store', array_merge($this->record, $invalidTrack)))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('track_positions');
    assertDatabaseCount('records', 0);
    assertDatabaseCount('tracks', 0);
    get(route('records.create'))
        ->assertSee('The track positions field is required.');
});

it('redirects and shows an error if the track position is not numeric', function () {
    $invalidTrack = $this->validTrack;
    $invalidTrack['track_positions'] = ['One'];
    post(route('records.store', array_merge($this->record, $invalidTrack)))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('track_positions');
    assertDatabaseCount('records', 0);
    assertDatabaseCount('tracks', 0);
    get(route('records.create'))
        ->assertSee('The track_positions.0 must be a number.');
});

it('adds a leading zero if the track position is a single digit (1-9)', function () {
    $validTrack = $this->validTrack;
    $validTrack['track_positions'][0] = '1';
    post(route('records.store', array_merge($this->record, $validTrack)))
        ->assertRedirect(route('records.index'))
        ->assertSessionDoesntHaveErrors();
    assertDatabaseCount('records', 1);
    assertDatabaseHas('tracks', ['position' => '01']);
});

it('redirects and shows an error if the track position is less than one ', function () {
    $invalidTrack = $this->validTrack;
    $invalidTrack['track_positions'] = ['00'];
    post(route('records.store', array_merge($this->record, $invalidTrack)))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('track_positions');
    assertDatabaseCount('records', 0);
    assertDatabaseCount('tracks', 0);
    get(route('records.create'))
        ->assertSee('The track_positions.0 must be at least 1.');
});

it('redirects and shows an error if the track position is more than two digits', function () {
    $invalidTrack = $this->validTrack;
    $invalidTrack['track_positions'] = ['100'];
    post(route('records.store', array_merge($this->record, $invalidTrack)))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('track_positions');
    assertDatabaseCount('records', 0);
    assertDatabaseCount('tracks', 0);
    get(route('records.create'))
        ->assertSee('The track_positions.0 must not have more than 2 digits.');
});

it('redirects and shows an error if the track title is not an array', function () {
    $invalidTrack = $this->validTrack;
    $invalidTrack['track_titles'] = '';
    post(route('records.store', array_merge($this->record, $invalidTrack)))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('track_titles');
    assertDatabaseCount('records', 0);
    assertDatabaseCount('tracks', 0);
    get(route('records.create'))
        ->assertSee('The track titles field is required.');
});

it('redirects and shows an error if the track title is an empty array', function () {
    $invalidTrack = $this->validTrack;
    $invalidTrack['track_titles'] = '';
    post(route('records.store', array_merge($this->record, $invalidTrack)))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('track_titles');
    assertDatabaseCount('records', 0);
    assertDatabaseCount('tracks', 0);
    get(route('records.create'))
        ->assertSee('The track titles field is required.');
});

it('redirects and shows an error if the duration is not an array', function () {
    $invalidTrack = $this->validTrack;
    $invalidTrack['track_durations'] = '';
    post(route('records.store', array_merge($this->record, $invalidTrack)))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('track_durations');
    assertDatabaseCount('records', 0);
    assertDatabaseCount('tracks', 0);
    get(route('records.create'))
        ->assertSee('The track durations field is required.');
});

it('redirects and shows an error if the duration is an empty array', function () {
    $invalidTrack = $this->validTrack;
    $invalidTrack['track_durations'] = '';
    post(route('records.store', array_merge($this->record, $invalidTrack)))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('track_durations');
    assertDatabaseCount('records', 0);
    assertDatabaseCount('tracks', 0);
    get(route('records.create'))
        ->assertSee('The track durations field is required.');
});

it('redirects and shows an error if the duration is not in the format minutes:seconds (00:00)', function () {
    $invalidTrack = $this->validTrack;
    $invalidTrack['track_durations'] = ['45'];
    post(route('records.store', array_merge($this->record, $invalidTrack)))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('track_durations');
    assertDatabaseCount('records', 0);
    assertDatabaseCount('tracks', 0);
    get(route('records.create'))
        ->assertSee('The track_durations.0 does not match the format i:s.');
});

it('redirects and shows an error if the mix is not an array', function () {
    $invalidTrack = $this->validTrack;
    $invalidTrack['track_mixes'] = '';
    post(route('records.store', array_merge($this->record, $invalidTrack)))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('track_mixes');
    assertDatabaseCount('records', 0);
    assertDatabaseCount('tracks', 0);
    get(route('records.create'))
        ->assertSee('The track mixes field is required.');

});

it('redirects shows an error if the track artists is not an array  on a various artists record', function () {
    $invalidTrack = $this->validTrack;
    $record = $this->record;
    $record['artist'] = 'Various Artists';
    $invalidTrack['track_artists'] = '';
    post(route('records.store', array_merge($record, $invalidTrack)))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('track_artists');
    assertDatabaseCount('records', 0);
    assertDatabaseCount('tracks', 0);
    get(route('records.create'))
        ->assertSee('The track artists field is required when artist is Various Artists.');
});

it('redirects shows an error if the track artists is an empty array  on a various artists record', function () {
    $invalidTrack = $this->validTrack;
    $record = $this->record;
    $record['artist'] = 'Various Artists';
    $invalidTrack['track_artists'] = [];
    post(route('records.store', array_merge($record, $invalidTrack)))
        ->assertRedirect(route('records.create'))
        ->assertSessionHasErrorsIn('track_artists');
    assertDatabaseCount('records', 0);
    assertDatabaseCount('tracks', 0);
    get(route('records.create'))
        ->assertSee('The track artists field is required when artist is Various Artists.');
});

it('ignores the track artist if the record artist is not various artists', function () {
    post(route('records.store', array_merge($this->record, $this->validTrack)))
        ->assertRedirect(route('records.index'))
        ->assertSessionHasNoErrors();
    assertDatabaseCount('records', 1);
    assertDatabaseCount('tracks', 1);
});


