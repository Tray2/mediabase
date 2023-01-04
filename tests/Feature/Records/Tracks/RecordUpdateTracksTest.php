<?php

use App\Models\Artist;
use App\Models\Country;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Record;
use App\Models\RecordLabel;
use App\Models\Track;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
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
    $this->country = Country::factory()->create();
    $this->record_label = RecordLabel::factory()->create();
    $this->validRecord = [
        'title' => 'Some Title',
        'release_year' => 1984,
        'barcode' => '9781398510784',
        'spine_code' => '11122554',
        'artist_id' => $this->artist->id,
        'genre_id' => $this->genre->id,
        'format_id' => $this->format->id,
        'country_id' => $this->country->id,
        'record_label_id' => $this->record_label->id,
    ];
    $this->record = Record::create($this->validRecord);

    $this->validRecord = array_merge($this->validRecord, [
        'artist' => $this->artist->name,
        'format_name' => $this->format->name,
        'genre_name' => $this->genre->name,
        'country_name' => $this->country->name,
        'record_label_name' => $this->record_label->name,
    ]);

    $this->validTrack = [
        'position' => '01',
        'title' => 'Some Track Title',
        'duration' => '03:20',
        'mix' => 'Some Mix',
        'record_id' => $this->record->id,
    ];
    $this->track = Track::create($this->validTrack);
    get(route('records.edit', $this->record));
});

it('updates a valid track without mix', function () {
    $updatedTrack = [
        'track_positions' => [$this->track->position],
        'track_titles' => ['Updated Track Title'],
        'track_durations' => [$this->track->duration],
        'track_mixes' => [''],
    ];
    put(route('records.update', $this->record), array_merge($this->validRecord, $updatedTrack))
        ->assertRedirect(route('records.index'))
        ->assertSessionDoesntHaveErrors();

    assertDatabaseHas('tracks', ['title' => 'Updated Track Title']);
});

it('updates a valid track with mix', function () {
    $updatedTrack = [
        'track_positions' => [$this->track->position],
        'track_titles' => ['Updated Track Title'],
        'track_durations' => [$this->track->duration],
        'track_mixes' => [$this->track->mix],
    ];
    put(route('records.update', $this->record), array_merge($this->validRecord, $updatedTrack))
        ->assertRedirect(route('records.index'))
        ->assertSessionDoesntHaveErrors();

    assertDatabaseHas('tracks', ['title' => 'Updated Track Title']);
});

it('updates a valid track with for a various artist record', function () {
    $updatedTrack = [
        'track_artists' => ['Updated Track Artist'],
        'track_positions' => [$this->track->position],
        'track_titles' => ['Updated Track Title'],
        'track_durations' => [$this->track->duration],
        'track_mixes' => [''],
    ];

    $record = $this->validRecord;
    $record['artist'] = 'Various Artists';

    put(route('records.update', $this->record), array_merge($record, $updatedTrack))
        ->assertRedirect(route('records.index'))
        ->assertSessionDoesntHaveErrors();
    assertDatabaseHas('artists', ['name' => 'Updated Track Artist']);
    assertDatabaseHas('tracks', ['title' => 'Updated Track Title']);
    assertDatabaseMissing('tracks', ['artist_id' => null]);
});

it('updates multiple valid tracks', function () {
    $validTrack = $this->validTrack;
    $validTrack['position'] = '02';
    $validTrack['title'] = 'Another Track';
    $validTrack['duration'] = '03:50';
    $validTrack['mix'] = '';
    $validTrack['record_id'] = $this->record->id;
    $track2 = Track::create($validTrack);

    $updatedTracks = [
        'track_positions' => [$this->track->position, $track2->position],
        'track_titles' => ['Updated Track Title', 'Another Updated Track Title'],
        'track_durations' => [$this->track->duration, $track2->duration],
        'track_mixes' => ['', ''],
    ];

    put(route('records.update', $this->record), array_merge($this->validRecord, $updatedTracks))
        ->assertRedirect(route('records.index'))
        ->assertSessionDoesntHaveErrors();
    assertDatabaseHas('tracks', ['title' => 'Another Updated Track Title']);
    assertDatabaseHas('tracks', ['title' => 'Updated Track Title']);
});

it('redirects and shows an error if the track position is not an array', function () {
    $updatedTrack = [
        'track_positions' => '',
        'track_titles' => [$this->track->title],
        'track_durations' => [$this->track->duration],
        'track_mixes' => [$this->track->mix],
    ];

    put(route('records.update', $this->record), array_merge($this->validRecord, $updatedTrack))
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('track_positions');
    get(route('records.edit', $this->record))
        ->assertSee('The track positions field is required.');
});

it('redirects and shows an error if the track position is an empty array', function () {
    $updatedTrack = [
        'track_positions' => [],
        'track_titles' => [$this->track->title],
        'track_durations' => [$this->track->duration],
        'track_mixes' => [$this->track->mix],
    ];

    put(route('records.update', $this->record), array_merge($this->validRecord, $updatedTrack))
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('track_positions');
    get(route('records.edit', $this->record))
        ->assertSee('The track positions field is required.');
});

it('redirects and shows an error if the track position is not numeric', function () {
    $updatedTrack = [
        'track_positions' => ['Ten'],
        'track_titles' => [$this->track->title],
        'track_durations' => [$this->track->duration],
        'track_mixes' => [$this->track->mix],
    ];

    put(route('records.update', $this->record), array_merge($this->validRecord, $updatedTrack))
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('track_positions');
    get(route('records.edit', $this->record))
        ->assertSee('The track_positions.0 must be a number.');
});

it('adds a leading zero if the track position is a single digit (1-9)', function () {
    $updatedTrack = [
        'track_positions' => ['1'],
        'track_titles' => [$this->track->title],
        'track_durations' => [$this->track->duration],
        'track_mixes' => [$this->track->mix],
    ];

    put(route('records.update', $this->record), array_merge($this->validRecord, $updatedTrack));
    assertDatabaseHas('tracks', ['position' => '01']);
});

it('redirects and shows an error if the track position is less than one ', function () {
    $updatedTrack = [
        'track_positions' => ['00'],
        'track_titles' => [$this->track->title],
        'track_durations' => [$this->track->duration],
        'track_mixes' => [$this->track->mix],
    ];

    put(route('records.update', $this->record), array_merge($this->validRecord, $updatedTrack))
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('track_positions');
    get(route('records.edit', $this->record))
        ->assertSee('The track_positions.0 must be at least 1.');
});

it('redirects and shows an error if the track position is more than two digits', function () {
    $updatedTrack = [
        'track_positions' => ['123'],
        'track_titles' => [$this->track->title],
        'track_durations' => [$this->track->duration],
        'track_mixes' => [$this->track->mix],
    ];

    put(route('records.update', $this->record), array_merge($this->validRecord, $updatedTrack))
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('track_positions');
    get(route('records.edit', $this->record))
        ->assertSee('The track_positions.0 must not have more than 2 digits.');
});

it('redirects and shows an error if the track title is not an array', function () {
    $updatedTrack = [
        'track_positions' => [$this->track->position],
        'track_titles' => '',
        'track_durations' => [$this->track->duration],
        'track_mixes' => [$this->track->mix],
    ];

    put(route('records.update', $this->record), array_merge($this->validRecord, $updatedTrack))
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('track_titles');
    get(route('records.edit', $this->record))
        ->assertSee('The track titles field is required.');
});

it('redirects and shows an error if the track title is an empty array', function () {
    $updatedTrack = [
        'track_positions' => [$this->track->position],
        'track_titles' => [],
        'track_durations' => [$this->track->duration],
        'track_mixes' => [$this->track->mix],
    ];

    put(route('records.update', $this->record), array_merge($this->validRecord, $updatedTrack))
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('track_titles');
    get(route('records.edit', $this->record))
        ->assertSee('The track titles field is required.');
});

it('redirects and shows an error if the duration is not an array', function () {
    $updatedTrack = [
        'track_positions' => [$this->track->position],
        'track_titles' => [$this->track->title],
        'track_durations' => '',
        'track_mixes' => [$this->track->mix],
    ];

    put(route('records.update', $this->record), array_merge($this->validRecord, $updatedTrack))
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('track_durations');
    get(route('records.edit', $this->record))
        ->assertSee('The track durations field is required.');
});

it('redirects and shows an error if the duration is an empty array', function () {
    $updatedTrack = [
        'track_positions' => [$this->track->position],
        'track_titles' => [$this->track->title],
        'track_durations' => [],
        'track_mixes' => [$this->track->mix],
    ];

    put(route('records.update', $this->record), array_merge($this->validRecord, $updatedTrack))
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('track_durations');
    get(route('records.edit', $this->record))
        ->assertSee('The track durations field is required.');
});

it('redirects and shows an error if the duration is not in the format minutes:seconds (00:00)', function () {
    $updatedTrack = [
        'track_positions' => [$this->track->position],
        'track_titles' => [$this->track->title],
        'track_durations' => ['45'],
        'track_mixes' => [$this->track->mix],
    ];

    put(route('records.update', $this->record), array_merge($this->validRecord, $updatedTrack))
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('track_durations');
    get(route('records.edit', $this->record))
        ->assertSee('The track_durations.0 does not match the format i:s.');
});

it('redirects and shows an error if the mix is not an array', function () {
    $updatedTrack = [
        'track_positions' => [$this->track->position],
        'track_titles' => [$this->track->title],
        'track_durations' => [$this->track->duration],
        'track_mixes' => '',
    ];

    put(route('records.update', $this->record), array_merge($this->validRecord, $updatedTrack))
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('track_mixes');
    get(route('records.edit', $this->record))
        ->assertSee('The track mixes field is required.');
});

it('redirects shows an error if the track artists is not an array on a various artists record', function () {
    $artist = Artist::create(['name' => 'Various Artists']);
    $validRecord = $this->validRecord;
    $validRecord['artist'] = $artist->name;

    $updatedTrack = [
        'track_artists' => '',
        'track_positions' => [$this->track->position],
        'track_titles' => [$this->track->title],
        'track_durations' => [$this->track->duration],
        'track_mixes' => [$this->track->mix],
    ];

    put(route('records.update', $this->record), array_merge($validRecord, $updatedTrack))
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('track_artists');
    get(route('records.edit', $this->record))
        ->assertSee('The track artists field is required when artist is Various Artists.');
});

it('redirects shows an error if the track artists is an empty array  on a various artists record', function () {
    $artist = Artist::create(['name' => 'Various Artists']);
    $validRecord = $this->validRecord;
    $validRecord['artist'] = $artist->name;

    $updatedTrack = [
        'track_artists' => [],
        'track_positions' => [$this->track->position],
        'track_titles' => [$this->track->title],
        'track_durations' => [$this->track->duration],
        'track_mixes' => [$this->track->mix],
    ];

    put(route('records.update', $this->record), array_merge($validRecord, $updatedTrack))
        ->assertRedirect(route('records.edit', $this->record))
        ->assertSessionHasErrorsIn('track_artists');
    get(route('records.edit', $this->record))
        ->assertSee('The track artists field is required when artist is Various Artists.');
});

it('ignores the track artist if the record artist is not various artists', function () {
    $updatedTrack = [
        'track_artists' => ['Some Track Artist'],
        'track_positions' => [$this->track->position],
        'track_titles' => [$this->track->title],
        'track_durations' => [$this->track->duration],
        'track_mixes' => [$this->track->mix],
    ];

    put(route('records.update', $this->record), array_merge($this->validRecord, $updatedTrack));
    assertDatabaseMissing('tracks', ['artist_id' => ! null]);
});

it('has the old track values if the validation fails', function () {
    $track = [
        'track_positions' => [$this->track->position],
        'track_titles' => [$this->track->title],
        'track_durations' => [$this->track->duration],
        'track_mixes' => [$this->track->mix],
    ];
    $invalidRecord = $this->validRecord;
    $invalidRecord['artist'] = '';
    put(route('records.update', $this->record), array_merge($invalidRecord, $track))
        ->assertRedirect(route('records.edit', $this->record));
    get(route('records.create'))
        ->assertOk()
        ->assertFormExists(fn (AssertForm $form) => $form->containsInput([
            'name' => 'track_positions[]',
            'value' => $track['track_positions'][0],
        ])
                ->containsInput([
                    'name' => 'track_titles[]',
                    'value' => $track['track_titles'][0],
                ])
                ->containsInput([
                    'name' => 'track_durations[]',
                    'value' => $track['track_durations'][0],
                ])
                ->containsInput([
                    'name' => 'track_mixes[]',
                    'value' => $track['track_mixes'][0],
                ])
        );
});

it('can handle old values for more than one track if validation fails', function () {
    $tracks = [
        'track_positions' => [$this->track->position, '02'],
        'track_titles' => [$this->track->title, 'Another Title'],
        'track_durations' => [$this->track->duration, '3:50'],
        'track_mixes' => [$this->track->mix, 'Album'],
    ];
    $invalidRecord = $this->validRecord;
    $invalidRecord['artist'] = '';
    put(route('records.update', $this->record), array_merge($invalidRecord, $tracks))
        ->assertRedirect(route('records.edit', $this->record));
    get(route('records.create'))
        ->assertOk()
        ->assertFormExists(fn (AssertForm $form) => $form->containsInput([
            'name' => 'track_positions[]',
            'value' => $tracks['track_positions'][0],
        ])
            ->containsInput([
                'name' => 'track_titles[]',
                'value' => $tracks['track_titles'][0],
            ])
            ->containsInput([
                'name' => 'track_durations[]',
                'value' => $tracks['track_durations'][0],
            ])
            ->containsInput([
                'name' => 'track_mixes[]',
                'value' => $tracks['track_mixes'][0],
            ])
            ->containsInput([
                'name' => 'track_positions[]',
                'value' => $tracks['track_positions'][1],
            ])
            ->containsInput([
                'name' => 'track_titles[]',
                'value' => $tracks['track_titles'][1],
            ])
            ->containsInput([
                'name' => 'track_durations[]',
                'value' => $tracks['track_durations'][1],
            ])
            ->containsInput([
                'name' => 'track_mixes[]',
                'value' => $tracks['track_mixes'][1],
            ]));
});

it('shows the old value for the track artist if validation fails for a various artists record', function () {
    $record = $this->validRecord;
    $record['artist'] = 'Various Artists';
    $record['title'] = '';

    $track = [
        'track_artists' => ['Public Enemy'],
        'track_positions' => [$this->track->position],
        'track_titles' => [$this->track->title],
        'track_durations' => [$this->track->duration],
        'track_mixes' => [$this->track->mix],
    ];
    put(route('records.update', $this->record), array_merge($record, $track))
        ->assertRedirect(route('records.edit', $this->record));
    get(route('records.create'))
        ->assertOk()
        ->assertFormExists(fn (AssertForm $form) => $form->containsInput([
            'name' => 'track_artists[]',
            'value' => $track['track_artists'][0],
        ])
        );
});
