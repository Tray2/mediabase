<?php

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Record;
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

it('lists records', function() {
    $fields = ['title', 'release_year',];
    $genre = Genre::factory()->create([
        'media_type_id' => $this->mediaTypeId,
    ]);
    $format = Format::factory()->create([
        'media_type_id' => $this->mediaTypeId,
    ]);
    $artist = Artist::factory()->create();
    [$record1, $record2] = Record::factory()
                            ->count(2)
                            ->create([
                                'artist_id' => $artist->id,
                                'genre_id' => $genre->id,
                                'format_id' => $format->id
                            ]);

    get(route('records.index'))
        ->assertOk()
        ->assertSeeText([
            ...$record1->only($fields),
            ...$record2->only($fields),
            $genre->name,
            $format->name,
            $artist->name,
        ]);
});

it('sorts records by artist', function () {
    Artist::factory()
        ->count(2)
        ->sequence(
            ['name' => 'Run Dmc',],
            ['name' => 'Public Enemy']
        )->has(Record::factory())
        ->create();

    get(route('records.index'))
        ->assertOk()
        ->assertSeeTextInOrder([
            'Public Enemy',
            'Run Dmc'
        ]);
});

it('sorts records by the same artist by released year', function () {
    Artist::factory()
        ->has(Record::factory()
        ->count(5)
        ->sequence(
            ['release_year' => 1986],
            ['release_year' => 1982],
            ['release_year' => 2006],
            ['release_year' => 1971],
            ['release_year' => 2004],
        ))
        ->create([
            'name' => 'Public Enemy',
        ]);

    get(route('records.index'))
        ->assertOk()
        ->assertSeeTextInOrder([
            1971,
            1982,
            1986,
            2004,
            2006,
        ]);
});

