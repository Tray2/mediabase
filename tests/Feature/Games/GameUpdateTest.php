<?php

use App\Models\Format;
use App\Models\Game;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Platform;
use App\Models\User;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use Sinnbeck\DomAssertions\Asserts\AssertForm;

beforeEach(function () {
    $mediaTypeId = MediaType::query()
        ->where('name', 'game')
        ->value('id');
    $this->genre = Genre::factory()->create([
        'media_type_id' => $mediaTypeId,
    ]);
    $this->format = Format::factory()->create([
        'media_type_id' => $mediaTypeId,
    ]);
    $this->game = Game::factory()->create([
        'title' => 'Some Title',
        'release_year' => 1984,
        'blurb' => 'Some boring text',
    ]);
    $this->platform = Platform::factory()->create([
        'name' => 'PS5',
    ]);
    $this->validGame = array_merge($this->game->toArray(), [
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'platform_name' => $this->platform->name,
    ]);

    $this->user = User::factory()->create();
    actingAs($this->user)->get(route('games.edit', $this->game));
});

it('updates a valid game', function () {
    $validGame = $this->validGame;
    $validGame['title'] = 'Some New Title';
    actingAs($this->user)->put(route('games.update', $this->game), $validGame)
        ->assertRedirect(route('games.index'));
    assertDatabaseHas('games', ['title' => 'Some New Title']);
    assertDatabaseCount('games', 1);
});

it('creates a new platform if the one passed does not exist in the database', function () {
    $validGame = $this->validGame;
    $validGame['platform_name'] = 'Fantasy';

    actingAs($this->user)->put(route('games.update', $this->game), $validGame)
        ->assertRedirect(route('games.index'));
    assertDatabaseCount('games', 1);
    assertDatabaseHas('platforms', ['name' => 'Fantasy']);
});

it('creates a new genre if the one passed does not exist in the database', function () {
    $validGame = $this->validGame;
    $validGame['genre_name'] = 'Fantasy';

    actingAs($this->user)->put(route('games.update', $this->game), $validGame)
        ->assertRedirect(route('games.index'));
    assertDatabaseCount('games', 1);
    assertDatabaseHas('genres', ['name' => 'Fantasy']);
});

it('creates a new format if the one passed does not exist in the database', function () {
    $validGame = $this->validGame;
    $validGame['format_name'] = 'Hardcover';

    actingAs($this->user)->put(route('games.update', $this->game), $validGame)
        ->assertRedirect(route('games.index'));
    assertDatabaseCount('games', 1);
    assertDatabaseHas('formats', ['name' => 'Hardcover']);
});

it('has the old values in the form if the validation fails', function () {
    $invalidGame = $this->validGame;
    $invalidGame['title'] = '';
    actingAs($this->user)->put(route('games.update', $this->game), $invalidGame)
        ->assertRedirect(route('games.edit', $this->game))
        ->assertSessionHasErrorsIn('title');
    actingAs($this->user)->get(route('games.edit', $this->game))
        ->assertOk()
        ->assertSeeText('The title field is required.')
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'name' => 'release_year',
                'value' => $this->validGame['release_year'],
            ])
                ->contains('textarea', [
                    'name' => 'blurb',
                    'value' => $this->validGame['blurb'],
                ])
                ->containsInput([
                    'name' => 'format_name',
                    'value' => $this->validGame['format_name'],
                ])
                ->containsInput([
                    'name' => 'platform_name',
                    'value' => $this->validGame['platform_name'],
                ])
                ->containsInput([
                    'name' => 'genre_name',
                    'value' => $this->validGame['genre_name'],
                ]);
        });
});

it('has the old title value in the form if the validation fails', function () {
    $invalidGame = $this->validGame;
    $invalidGame['blurb'] = '';

    actingAs($this->user)->put(route('games.update', $this->game), $invalidGame)
        ->assertRedirect(route('games.edit', $this->game))
        ->assertSessionHasErrorsIn('blurb');

    actingAs($this->user)->get(route('games.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'name' => 'title',
                'value' => $this->validGame['title'],
            ]);
        });
});
