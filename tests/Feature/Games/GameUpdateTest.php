<?php

use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Game;
use App\Models\Platform;
use Carbon\Carbon;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\put;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use Sinnbeck\DomAssertions\Asserts\AssertForm;

uses(FastRefreshDatabase::class);

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
        'name' => 'PS5'
    ]);
    $this->validGame = array_merge($this->game->toArray(), [
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'platform_name' => $this->platform->name,
    ]);
    get(route('games.edit', $this->game));
});

it('updates a valid game', function () {
    $validGame = $this->validGame;
    $validGame['title'] = 'Some New Title';
    put(route('games.update', $this->game), $validGame)
        ->assertRedirect(route('games.index'));
    assertDatabaseHas('games', ['title' => 'Some New Title']);
    assertDatabaseCount('games', 1);
});

it('redirects and shows an error if the title is missing', function () {
    $invalidGame = $this->validGame;
    $invalidGame['title'] = '';

    put(route('games.update', $this->game), $invalidGame)
        ->assertRedirect(route('games.edit', $this->game))
        ->assertSessionHasErrorsIn('title');
    get(route('games.edit', $this->game))
        ->assertSeeText('The title field is required.');
});

it('redirects and shows an error if the release year is missing', function () {
    $invalidGame = $this->validGame;
    $invalidGame['release_year'] = '';

    put(route('games.update', $this->game), $invalidGame)
        ->assertRedirect(route('games.edit', $this->game))
        ->assertSessionHasErrorsIn('release_year');
    get(route('games.edit', $this->game))
        ->assertSeeText('The release year field is required.');
});

it('shows an error if the release year is not numeric', function () {
    $invalidGame = $this->validGame;
    $invalidGame['release_year'] = 'Nineteen Eighty Four';

    put(route('games.update', $this->game), $invalidGame)
        ->assertRedirect(route('games.edit', $this->game))
        ->assertSessionHasErrorsIn('release_year');
    get(route('games.edit', $this->game))
        ->assertSeeText('The release year must be a number.');
});

it('shows an error if the release year is less than four digits', function () {
    $invalidGame = $this->validGame;
    $invalidGame['release_year'] = 123;

    put(route('games.update', $this->game), $invalidGame)
        ->assertRedirect(route('games.edit', $this->game))
        ->assertSessionHasErrorsIn('release_year');
    get(route('games.edit', $this->game))
        ->assertSeeText('The release year must have at least 4 digits.');
});

it('shows an error if the release year is more than four digits', function () {
    $invalidGame = $this->validGame;
    $invalidGame['release_year'] = 12345;

    put(route('games.update', $this->game), $invalidGame)
        ->assertRedirect(route('games.edit', $this->game))
        ->assertSessionHasErrorsIn('release_year');
    get(route('games.edit', $this->game))
        ->assertSeeText('The release year must not have more than 4 digits.');
});

it('shows an error if the release year is more than a year into the future', function () {
    $invalidGame = $this->validGame;
    $invalidGame['release_year'] = Carbon::now()->addYear(2)->year;

    put(route('games.update', $this->game), $invalidGame)
        ->assertRedirect(route('games.edit', $this->game))
        ->assertSessionHasErrorsIn('release_year');
    get(route('games.edit', $this->game))
        ->assertSeeText('The release year must be between 1800 and '.Carbon::now()->addYear(1)->year.'.');
});


it('shows an error if the blurb is missing', function () {
    $invalidGame = $this->validGame;
    $invalidGame['blurb'] = '';

    put(route('games.update', $this->game), $invalidGame)
        ->assertRedirect(route('games.edit', $this->game))
        ->assertSessionHasErrorsIn('release_year');
    get(route('games.edit', $this->game))
        ->assertSeeText('The blurb field is required.');
});

it('shows an error if the blurb word count is less than three', function () {
    $invalidGame = $this->validGame;
    $invalidGame['blurb'] = 'This';

    put(route('games.update', $this->game), $invalidGame)
        ->assertRedirect(route('games.edit', $this->game))
        ->assertSessionHasErrorsIn('release_year');
    get(route('games.edit', $this->game))
        ->assertSeeText('The blurb must be at least 3 words.');
});

it('shows an error if the platform is missing', function () {
    $invalidGame = $this->validGame;
    $invalidGame['platform_name'] = '';

    put(route('games.update', $this->game), $invalidGame)
        ->assertRedirect(route('games.edit', $this->game))
        ->assertSessionHasErrorsIn('platform_name');
    get(route('games.edit', $this->game))
        ->assertSeeText('The platform name field is required.');
});

it('creates a new platform if the one passed does not exist in the database', function () {
    $validGame = $this->validGame;
    $validGame['platform_name'] = 'Fantasy';

    put(route('games.update', $this->game), $validGame)
        ->assertRedirect(route('games.index'));
    assertDatabaseCount('games', 1);
    assertDatabaseHas('platforms', ['name' => 'Fantasy']);
});

it('shows an error if the genre is missing', function () {
    $invalidGame = $this->validGame;
    $invalidGame['genre_name'] = '';

    put(route('games.update', $this->game), $invalidGame)
        ->assertRedirect(route('games.edit', $this->game))
        ->assertSessionHasErrorsIn('genre_name');
    get(route('games.edit', $this->game))
        ->assertSeeText('The genre name field is required.');
});

it('creates a new genre if the one passed does not exist in the database', function () {
    $validGame = $this->validGame;
    $validGame['genre_name'] = 'Fantasy';

    put(route('games.update', $this->game), $validGame)
        ->assertRedirect(route('games.index'));
    assertDatabaseCount('games', 1);
    assertDatabaseHas('genres', ['name' => 'Fantasy']);
});

it('shows an error if the format is missing', function () {
    $invalidGame = $this->validGame;
    $invalidGame['format_name'] = '';

    put(route('games.update', $this->game), $invalidGame)
        ->assertRedirect(route('games.edit', $this->game))
        ->assertSessionHasErrorsIn('format_name');
    get(route('games.edit', $this->game))
        ->assertSeeText('The format name field is required.');
});

it('creates a new format if the one passed does not exist in the database', function () {
    $validGame = $this->validGame;
    $validGame['format_name'] = 'Hardcover';

    put(route('games.update', $this->game), $validGame)
        ->assertRedirect(route('games.index'));
    assertDatabaseCount('games', 1);
    assertDatabaseHas('formats', ['name' => 'Hardcover']);
});

it('has the old values in the form if the validation fails', function () {
    $invalidGame = $this->validGame;
    $invalidGame['title'] = '';
    put(route('games.update', $this->game), $invalidGame)
        ->assertRedirect(route('games.edit', $this->game))
        ->assertSessionHasErrorsIn('title');
    get(route('games.edit', $this->game))
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

    put(route('games.update', $this->game), $invalidGame)
        ->assertRedirect(route('games.edit', $this->game))
        ->assertSessionHasErrorsIn('blurb');

    get(route('games.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'name' => 'title',
                'value' => $this->validGame['title'],
            ]);
        });
});
