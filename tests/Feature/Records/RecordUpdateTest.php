<?php

use App\Models\Artist;
use App\Models\Country;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Record;
use App\Models\RecordLabel;
use App\Models\User;
use Carbon\Carbon;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\put;
use Sinnbeck\DomAssertions\Asserts\AssertForm;

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
    $this->user = User::factory()->create();
    actingAs($this->user)->get(route('records.edit', $this->record));
});

it('updates a valid record', function () {
    $validRecord = $this->validRecord;
    $validRecord['title'] = 'Some Updated Title';
    put(route('records.update', $this->record), $validRecord)
        ->assertRedirect(route('records.index'));
    assertDatabaseHas('records', ['title' => 'Some Updated Title']);
    assertDatabaseCount('records', 1);
});

it('creates a new artist if the one passed does not exist in the database', function () {
    $validRecord = $this->validRecord;
    $validRecord['artist'] = 'Public Enemy';

    put(route('records.update', $this->record), $validRecord)
        ->assertRedirect(route('records.index'));
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

it('creates a new country if the one passed does not exist in the database', function () {
    $validRecord = $this->validRecord;
    $validRecord['country_name'] = 'Fantasy';

    put(route('records.update', $this->record), $validRecord)
        ->assertRedirect(route('records.index'));
    assertDatabaseCount('records', 1);
    assertDatabaseHas('countries', ['name' => 'Fantasy']);
});

it('creates a new format if the one passed does not exist in the database', function () {
    $validRecord = $this->validRecord;
    $validRecord['format_name'] = 'Hardcover';

    put(route('records.update', $this->record), $validRecord)
        ->assertRedirect(route('records.index'));
    assertDatabaseCount('records', 1);
    assertDatabaseHas('formats', ['name' => 'Hardcover']);
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
    actingAs($this->user)->get(route('records.edit', $this->record))
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
    actingAs($this->user)->get(route('records.edit', $this->record))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'name' => 'title',
                'value' => $this->validRecord['title'],
            ]);
        });
});
