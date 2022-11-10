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
use function Pest\Laravel\assertDatabaseHas;
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

    $this->validRecord = array_merge($this->validRecord, [
        'artist' => $this->artist->name,
        'format_name' => $this->format->name,
        'genre_name' => $this->genre->name,
        'country_name' => $this->country->name,
        'record_label_name' => $this->record_label->name
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


it('updates a valid track', function () {
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
