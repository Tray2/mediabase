<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);


it('has a track positions array field', function () {
    get(route('records.create'))
        ->assertSee([
            'for="track_positions"',
            'id="track_positions"',
            'name="track_positions[]"'
        ], false);
});

it('has a track titles array field', function () {
    get(route('records.create'))
        ->assertSee([
            'for="track_titles"',
            'id="track_titles"',
            'name="track_titles[]"'
        ], false);
});

it('has a track durations array field', function () {
    get(route('records.create'))
        ->assertSee([
            'for="track_durations"',
            'id="track_durations"',
            'name' => 'track_durations[]'
        ], false);
});

it('has a track mixes array field', function () {
    get(route('records.create'))
        ->assertSee([
            'for="track_mixes"',
            'id="track_mixes"',
            'name' => 'track_mixes[]'
        ], false);
});

it('has a track artists array field', function () {
    get(route('records.create'))
        ->assertSee([
            'for="track_artists"',
            'id="track_artists"',
            'list="artists"',
            'name' => 'track_artists[]'
        ], false);
});
