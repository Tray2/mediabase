<?php

use App\Models\Artist;
use App\Models\Country;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Record;
use App\Models\RecordLabel;
use Database\Seeders\MediaTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function() {
    $this->seed(MediaTypeSeeder::class);
    $this->mediaTypeId = MediaType::query()
        ->where('name', 'record')
        ->value('id');
});

it('shows all the information about  a record', function() {
    $record = Record::factory()
        ->for($artist = Artist::factory()->create())
        ->for($format = Format::factory()->create([
            'media_type_id' => $this->mediaTypeId,
        ]))
        ->for($genre = Genre::factory()->create())
        ->for($recordLabel = RecordLabel::factory()->create())
        ->for($country = Country::factory()->create())
        ->create();

    get(route('records.show', $record))
        ->assertOk()
        ->assertSeeText([
            $artist->name,
            $record->title,
            $record->spine_code,
            $record->released,
            $record->barcode,
            $genre->name,
            $format->name,
            $recordLabel->name,
            $country->name,
        ]);
});
