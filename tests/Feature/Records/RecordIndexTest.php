<?php

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Record;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('lists records', function() {
    $fields = ['title', 'released_year',];
    $genre = Genre::factory()->create();
    $format = Format::factory()->create();
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
    $years = Artist::factory()
        ->has(Record::factory())
        ->count(5)
        ->create([
            'name' => 'Public Enemy',
        ])
        ->pluck('released')
        ->sort()
        ->toArray();

    get(route('records.index'))
        ->assertOk()
        ->assertSeeTextInOrder($years);
});

