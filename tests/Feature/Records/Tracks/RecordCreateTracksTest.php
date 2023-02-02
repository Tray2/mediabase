<?php

use App\Models\User;
use Sinnbeck\DomAssertions\Asserts\AssertForm;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('has a track positions array field', function () {
    actingAs($this->user)->get(route('records.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'track_positions',
            ])
                ->containsInput([
                    'id' => 'track_positions',
                    'name' => 'track_positions[]',
                ]);
        });
});

it('has a track titles array field', function () {
    actingAs($this->user)->get(route('records.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'track_titles',
            ])
                ->containsInput([
                    'id' => 'track_titles',
                    'name' => 'track_titles[]',
                ]);
        });
});

it('has a track durations array field', function () {
    actingAs($this->user)->get(route('records.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'track_durations',
            ])
                ->containsInput([
                    'id' => 'track_durations',
                    'name' => 'track_durations[]',
                ]);
        });
});

it('has a track mixes array field', function () {
    actingAs($this->user)->get(route('records.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'track_mixes',
            ])
                ->containsInput([
                    'id' => 'track_mixes',
                    'name' => 'track_mixes[]',
                ]);
        });
});

it('has a track artists array field', function () {
    actingAs($this->user)->get(route('records.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'track_artists',
            ])
                ->containsInput([
                    'id' => 'track_artists',
                    'name' => 'track_artists[]',
                    'list' => 'artists',
                ]);
        });
});
