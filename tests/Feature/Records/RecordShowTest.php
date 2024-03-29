<?php

use App\Models\Artist;
use App\Models\Country;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Record;
use App\Models\RecordLabel;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->mediaTypeId = MediaType::query()
        ->where('name', 'record')
        ->value('id');
});

it('shows all the information about  a record', function () {
    $record = Record::factory()
        ->for($artist = Artist::factory()->create())
        ->for($format = Format::factory()->create([
            'media_type_id' => $this->mediaTypeId,
        ]))
        ->for($genre = Genre::factory()->create([
            'media_type_id' => $this->mediaTypeId,
        ]))
        ->for($recordLabel = RecordLabel::factory()->create())
        ->for($country = Country::factory()->create())
        ->create();

    get(route('records.show', $record))
        ->assertOk()
        ->assertSeeText([
            $artist->name,
            $record->title,
            $record->spine_code,
            $record->release_year,
            $record->barcode,
            $genre->name,
            $format->name,
            $recordLabel->name,
            $country->name,
        ]);
});
