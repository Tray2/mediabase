<?php

use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Platform;
use Carbon\Carbon;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use Sinnbeck\DomAssertions\Asserts\AssertForm;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(FastRefreshDatabase::class);

beforeEach(function () {
    $mediaTypeId = MediaType::query()
        ->where('name', 'game')
        ->value('id');
    $this->genre = Genre::factory()->create(['media_type_id' => $mediaTypeId]);
    $this->format = Format::factory()->create(['media_type_id' => $mediaTypeId]);
    $this->platform = Platform::factory()->create();
    $this->validGame = [
        'title' => 'Some Title',
        'release_year' => 1984,
        'blurb' => 'Some boring text',
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'platform_name' => $this->platform->name,
    ];
    get(route('games.create'));
});

it('stores a valid game', function () {
    post(route('games.store', $this->validGame))
        ->assertRedirect(route('games.index'));
    assertDatabaseCount('games', 1);
});

it('redirects and shows an error if the title is missing', function () {
    $invalidGame = $this->validGame;
    $invalidGame['title'] = '';

    post(route('games.store', $invalidGame))
        ->assertRedirect(route('games.create'))
        ->assertSessionHasErrorsIn('title');
    get(route('games.create'))
        ->assertSeeText('The title field is required.');
    assertDatabaseCount('games', 0);
});

it('redirects and shows an error if the release year is missing', function () {
    $invalidGame = $this->validGame;
    $invalidGame['release_year'] = '';

    post(route('games.store', $invalidGame))
        ->assertRedirect(route('games.create'))
        ->assertSessionHasErrorsIn('release_year');
    get(route('games.create'))
        ->assertSeeText('The release year field is required.');
    assertDatabaseCount('games', 0);
});

it('shows an error if the release year is not numeric', function () {
    $invalidGame = $this->validGame;
    $invalidGame['release_year'] = 'Nineteen Eighty Four';

    post(route('games.store', $invalidGame))
        ->assertRedirect(route('games.create'))
        ->assertSessionHasErrorsIn('release_year');
    get(route('games.create'))
        ->assertSeeText('The release year must be a number.');
    assertDatabaseCount('games', 0);
});

it('shows an error if the release year is less than four digits', function () {
    $invalidGame = $this->validGame;
    $invalidGame['release_year'] = 123;

    post(route('games.store', $invalidGame))
        ->assertRedirect(route('games.create'))
        ->assertSessionHasErrorsIn('release_year');
    get(route('games.create'))
        ->assertSeeText('The release year must have at least 4 digits.');
    assertDatabaseCount('games', 0);
});

it('shows an error if the release year is more than four digits', function () {
    $invalidGame = $this->validGame;
    $invalidGame['release_year'] = 12345;

    post(route('games.store', $invalidGame))
        ->assertRedirect(route('games.create'))
        ->assertSessionHasErrorsIn('release_year');
    get(route('games.create'))
        ->assertSeeText('The release year must not have more than 4 digits.');
    assertDatabaseCount('games', 0);
});

it('shows an error if the release year is more than a year into the future', function () {
    $invalidGame = $this->validGame;
    $invalidGame['release_year'] = Carbon::now()->addYear(2)->year;

    post(route('games.store', $invalidGame))
        ->assertRedirect(route('games.create'))
        ->assertSessionHasErrorsIn('release_year');
    get(route('games.create'))
        ->assertSeeText('The release year must be between 1800 and '.Carbon::now()->addYear(1)->year.'.');
    assertDatabaseCount('games', 0);
});

it('shows an error if the blurb is missing', function () {
    $invalidGame = $this->validGame;
    $invalidGame['blurb'] = '';

    post(route('games.store', $invalidGame))
        ->assertRedirect(route('games.create'))
        ->assertSessionHasErrorsIn('published_year');
    get(route('games.create'))
        ->assertSeeText('The blurb field is required.');
    assertDatabaseCount('games', 0);
});

it('shows an error if the blurb word count is less than three', function () {
    $invalidGame = $this->validGame;
    $invalidGame['blurb'] = 'This';

    post(route('games.store', $invalidGame))
        ->assertRedirect(route('games.create'))
        ->assertSessionHasErrorsIn('published_year');
    get(route('games.create'))
        ->assertSeeText('The blurb must be at least 3 words.');
    assertDatabaseCount('games', 0);
});

it('shows an error if the genre is missing', function () {
    $invalidGame = $this->validGame;
    $invalidGame['genre_name'] = '';

    post(route('games.store', $invalidGame))
        ->assertRedirect(route('games.create'))
        ->assertSessionHasErrorsIn('genre_name');
    get(route('games.create'))
        ->assertSeeText('The genre name field is required.');
    assertDatabaseCount('games', 0);
});

it('creates a new genre if the one passed does not exist in the database', function () {
    $validGame = $this->validGame;
    $validGame['genre_name'] = 'Fantasy';

    post(route('games.store', $validGame))
        ->assertRedirect(route('games.index'));
    assertDatabaseCount('games', 1);
    assertDatabaseHas('genres', ['name' => 'Fantasy']);
});

it('shows an error if the format is missing', function () {
    $invalidGame = $this->validGame;
    $invalidGame['format_name'] = '';

    post(route('games.store', $invalidGame))
        ->assertRedirect(route('games.create'))
        ->assertSessionHasErrorsIn('format_name');
    get(route('games.create'))
        ->assertSeeText('The format name field is required.');
    assertDatabaseCount('games', 0);
});

it('creates a new format if the one passed does not exist in the database', function () {
    $validGame = $this->validGame;
    $validGame['format_name'] = 'Hardcover';

    post(route('games.store', $validGame))
        ->assertRedirect(route('games.index'));
    assertDatabaseCount('games', 1);
    assertDatabaseHas('formats', ['name' => 'Hardcover']);
});

it('shows an error if the platform is missing', function () {
    $invalidGame = $this->validGame;
    $invalidGame['platform_name'] = '';

    post(route('games.store', $invalidGame))
        ->assertRedirect(route('games.create'))
        ->assertSessionHasErrorsIn('platform_name');
    get(route('games.create'))
        ->assertSeeText('The platform name field is required.');
    assertDatabaseCount('games', 0);
});

it('creates a new platform if the one passed does not exist in the database', function () {
    $validGame = $this->validGame;
    $validGame['platform_name'] = 'Fantasy';

    post(route('games.store', $validGame))
        ->assertRedirect(route('games.index'));
    assertDatabaseCount('games', 1);
    assertDatabaseHas('platforms', ['name' => 'Fantasy']);
});


it('has the old values in the form if the validation fails', function () {
    $invalidGame = $this->validGame;
    $invalidGame['title'] = '';

    post(route('games.store', $invalidGame))
        ->assertRedirect(route('games.create'))
        ->assertSessionHasErrorsIn('title');
    get(route('games.create'))
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
                    'name' => 'genre_name',
                    'value' => $this->validGame['genre_name'],
                ])
                ->containsInput([
                    'name' => 'platform_name',
                    'value' => $this->validGame['platform_name'],
                ]);
        });
});

it('has the old title value in the form if the validation fails', function () {
    $invalidGame = $this->validGame;
    $invalidGame['blurb'] = '';

    post(route('games.store', $invalidGame))
        ->assertRedirect(route('games.create'))
        ->assertSessionHasErrorsIn('title');
    get(route('games.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'name' => 'title',
                'value' => $this->validGame['title'],
            ]);
        });
});
