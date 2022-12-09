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
use Sinnbeck\DomAssertions\Asserts\AssertForm;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\get;
use function Pest\Laravel\put;

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

    $this->validTrack = [
        'position' => '01',
        'title' => 'Some Track Title',
        'duration' => '03:20',
        'mix' => 'Some Mix',
        'record_id' => $this->record->id,
    ];
    $this->track = Track::create($this->validTrack);
});

it('has the position field', function() {
    get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'track_positions'
             ])
                ->containsInput([
                    'id' => 'track_positions',
                    'name' => 'track_positions[]',
                    'value' => $this->validTrack['position']
                ]);
        });
});

it('has the track titles field', function() {
    get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'track_titles'
             ])
                ->containsInput([
                    'id' => 'track_titles',
                    'name' => 'track_titles[]',
                    'value' => $this->validTrack['title']
                ]);
        });
});

it('has the track durations field', function() {
    get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'track_durations'
             ])
                ->containsInput([
                    'id' => 'track_durations',
                    'name' => 'track_durations[]',
                    'value' => $this->validTrack['duration']
                ]);
        });
});

it('has the track artists field', function() {
    get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'track_artists'
             ])
                ->containsInput([
                    'id' => 'track_artists',
                    'name' => 'track_artists[]'
                ]);
        });
});

it('has the track mix field', function() {
    get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'track_mixes'
             ])
                ->containsInput([
                    'id' => 'track_mixes',
                    'name' => 'track_mixes[]',
                    'value' => $this->validTrack['mix']
                ]);
        });
});

it('handles multiple tracks', function () {
    $validTrack = $this->validTrack;
    $validTrack['position'] = '02';
    $trackTwo = Track::create($validTrack);
    get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(fn (AssertForm $form) =>
            $form->containsInput([
                    'name' => 'track_positions[]',
                    'value' => $this->validTrack['position']
                ])
                ->containsInput([
                    'name' => 'track_positions[]',
                    'value' => $trackTwo->postion
                ])
        );
});
