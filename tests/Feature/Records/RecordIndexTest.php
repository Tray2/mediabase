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
