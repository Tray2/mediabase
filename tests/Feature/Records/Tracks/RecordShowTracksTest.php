<?php

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Record;
use App\Models\Track;
use function Pest\Laravel\get;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;

uses(FastRefreshDatabase::class);

it('shows a list of the records tracks', function () {
    $mediaId = MediaType::query()
        ->where('name', 'record')
        ->value('id');
    $record = Record::factory()
        ->for(Artist::factory())
        ->for(Genre::factory(['media_type_id' => $mediaId]))
        ->for(Format::factory(['media_type_id' => $mediaId]))
        ->has(Track::factory()
            ->count(4)
            ->sequence(
                ['position' => '01', 'title' => 'Track 1', 'duration' => '2:00'],
                ['position' => '02', 'title' => 'Track 2', 'duration' => '2:03'],
                ['position' => '03', 'title' => 'Track 3', 'duration' => '2:05', 'mix' => 'Track 3 Remix'],
                ['position' => '04', 'title' => 'Track 4', 'duration' => '2:08']
            ), 'tracks')
        ->create();

    get(route('records.show', $record))
        ->assertOk()
        ->assertSeeInOrder([
            '01', 'Track 1', '2:00',
            '02', 'Track 2', '2:03',
            '03', 'Track 3', '2:05', 'Track 3 Remix',
            '04', 'Track 4', '2:08',
        ]);
});

it('displays the track artist if it is a various artist compilation record', function () {
    $mediaId = MediaType::query()
        ->where('name', 'record')
        ->value('id');
    $trackArtist = Artist::factory()
        ->create(['name' => 'Public Enemy']);
    $record = Record::factory()
        ->for(Artist::factory()
            ->state(['name' => 'Various Artists']))
        ->for(Genre::factory(['media_type_id' => $mediaId]))
        ->for(Format::factory(['media_type_id' => $mediaId]))
        ->has(Track::factory()
            ->state([
                'position' => '01',
                'artist_id' => $trackArtist->id,
                'title' => 'Track 1',
                'duration' => '2:00', ]
            ))
        ->create();

    get(route('records.show', $record))
        ->assertOk()
        ->assertSeeInOrder([
            '01', 'Public Enemy', 'Track 1', '2:00',
        ]);
});
