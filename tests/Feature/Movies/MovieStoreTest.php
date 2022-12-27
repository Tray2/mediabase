<?php

use App\Models\Actor;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use Carbon\Carbon;
use Database\Seeders\MediaTypeSeeder;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use Sinnbeck\DomAssertions\Asserts\AssertForm;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(FastRefreshDatabase::class);

beforeEach(function () {
    $this->seed(MediaTypeSeeder::class);
    $mediaTypeId = MediaType::query()
        ->where('name', 'movie')
        ->value('id');
    $this->genre = Genre::factory()->create(['media_type_id' => $mediaTypeId]);
    $this->format = Format::factory()->create(['media_type_id' => $mediaTypeId]);
    $this->actor = Actor::factory()->create();
    $this->validMovie = [
        'title' => 'Some Title',
        'release_year' => 1984,
        'runtime' => 94,
        'blurb' => 'Some boring text',
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'actor' => ["{$this->actor->first_name} {$this->actor->last_name}"],
    ];
    get(route('movies.create'));
});


it('stores a valid movie', function () {
    post(route('movies.store', $this->validMovie))
        ->assertRedirect(route('movies.index'));
    assertDatabaseCount('movies', 1);
    assertDatabaseCount('actor_movie', 1);
});


it('stores a valid movie with multiple actors', function () {
    $actor = Actor::factory()->create();
    $validMovie = $this->validMovie;
    $validMovie['actor'] = [
        "{$this->actor->first_name} {$this->actor->last_name}",
        "{$actor->first_name} {$actor->last_name}",
    ];

    post(route('movies.store', $validMovie))
        ->assertRedirect(route('movies.index'));
    assertDatabaseCount('movies', 1);
    assertDatabaseCount('actor_movie', 2);
});

it('redirects and shows an error if the title is missing', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['title'] = '';

    post(route('movies.store', $invalidMovie))
        ->assertRedirect(route('movies.create'))
        ->assertSessionHasErrorsIn('title');
    get(route('movies.create'))
        ->assertSeeText('The title field is required.');
    assertDatabaseCount('movies', 0);
    assertDatabaseCount('actor_movie', 0);
});

it('redirects and shows an error if the release year is missing', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['release_year'] = '';

    post(route('movies.store', $invalidMovie))
        ->assertRedirect(route('movies.create'))
        ->assertSessionHasErrorsIn('release_year');
    get(route('movies.create'))
        ->assertSeeText('The release year field is required.');
    assertDatabaseCount('movies', 0);
    assertDatabaseCount('actor_movie', 0);
});

it('shows an error if the release year is not numeric', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['release_year'] = 'Nineteen Eighty Four';

    post(route('movies.store', $invalidMovie))
        ->assertRedirect(route('movies.create'))
        ->assertSessionHasErrorsIn('release_year');
    get(route('movies.create'))
        ->assertSeeText('The release year must be a number.');
    assertDatabaseCount('movies', 0);
    assertDatabaseCount('actor_movie', 0);
});

it('shows an error if the release year is less than four digits', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['release_year'] = 123;

    post(route('movies.store', $invalidMovie))
        ->assertRedirect(route('movies.create'))
        ->assertSessionHasErrorsIn('release_year');
    get(route('movies.create'))
        ->assertSeeText('The release year must have at least 4 digits.');
    assertDatabaseCount('movies', 0);
    assertDatabaseCount('actor_movie', 0);
});

it('shows an error if the release year is more than four digits', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['release_year'] = 12345;

    post(route('movies.store', $invalidMovie))
        ->assertRedirect(route('movies.create'))
        ->assertSessionHasErrorsIn('release_year');
    get(route('movies.create'))
        ->assertSeeText('The release year must not have more than 4 digits.');
    assertDatabaseCount('movies', 0);
    assertDatabaseCount('actor_movie', 0);
});

it('shows an error if the release year is more than a year into the future', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['release_year'] = Carbon::now()->addYear(2)->year;

    post(route('movies.store', $invalidMovie))
        ->assertRedirect(route('movies.create'))
        ->assertSessionHasErrorsIn('release_year');
    get(route('movies.create'))
        ->assertSeeText('The release year must be between 1800 and '.Carbon::now()->addYear(1)->year.'.');
    assertDatabaseCount('movies', 0);
    assertDatabaseCount('actor_movie', 0);
});

it('redirects and shows an error if the runtime is missing', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['runtime'] = '';

    post(route('movies.store', $invalidMovie))
        ->assertRedirect(route('movies.create'))
        ->assertSessionHasErrorsIn('runtime');
    get(route('movies.create'))
        ->assertSeeText('The runtime field is required.');
    assertDatabaseCount('movies', 0);
    assertDatabaseCount('actor_movie', 0);
});

it('shows an error if the runtime is not numeric', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['runtime'] = 'Ninty three';

    post(route('movies.store', $invalidMovie))
        ->assertRedirect(route('movies.create'))
        ->assertSessionHasErrorsIn('runtime');
    get(route('movies.create'))
        ->assertSeeText('The runtime must be a number.');
    assertDatabaseCount('movies', 0);
    assertDatabaseCount('actor_movie', 0);
});

it('shows an error if the runtime is less than 2 digits', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['runtime'] = 1;

    post(route('movies.store', $invalidMovie))
        ->assertRedirect(route('movies.create'))
        ->assertSessionHasErrorsIn('runtime');
    get(route('movies.create'))
        ->assertSeeText('The runtime must have at least 2 digits.');
    assertDatabaseCount('movies', 0);
    assertDatabaseCount('actor_movie', 0);
});

it('shows an error if the runtime is more than 3 digits', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['runtime'] = 4444;

    post(route('movies.store', $invalidMovie))
        ->assertRedirect(route('movies.create'))
        ->assertSessionHasErrorsIn('runtime');
    get(route('movies.create'))
        ->assertSeeText('The runtime must not have more than 3 digits.');
    assertDatabaseCount('movies', 0);
    assertDatabaseCount('actor_movie', 0);
});

it('shows an error if the blurb is missing', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['blurb'] = '';

    post(route('movies.store', $invalidMovie))
        ->assertRedirect(route('movies.create'))
        ->assertSessionHasErrorsIn('published_year');
    get(route('movies.create'))
        ->assertSeeText('The blurb field is required.');
    assertDatabaseCount('movies', 0);
    assertDatabaseCount('actor_movie', 0);
});

it('shows an error if the blurb word count is less than three', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['blurb'] = 'This';

    post(route('movies.store', $invalidMovie))
        ->assertRedirect(route('movies.create'))
        ->assertSessionHasErrorsIn('published_year');
    get(route('movies.create'))
        ->assertSeeText('The blurb must be at least 3 words.');
    assertDatabaseCount('movies', 0);
    assertDatabaseCount('actor_movie', 0);
});

it('shows an error if the actor is missing', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['actor'] = '';

    post(route('movies.store', $invalidMovie))
        ->assertRedirect(route('movies.create'))
        ->assertSessionHasErrorsIn('actor');
    get(route('movies.create'))
        ->assertSeeText('The actor field is required.');

    assertDatabaseCount('movies', 0);
    assertDatabaseCount('actor_movie', 0);
});

it('creates a new actor if the one passed does not exist in the database', function () {
    $validMovie = $this->validMovie;
    $validMovie['actor'] = ['Robert Jordan'];

    post(route('movies.store', $validMovie))
        ->assertRedirect(route('movies.index'));
    assertDatabaseCount('movies', 1);
    assertDatabaseCount('actor_movie', 1);
    assertDatabaseHas('actors', ['last_name' => 'Jordan', 'first_name' => 'Robert']);
});

it('shows an error if the genre is missing', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['genre_name'] = '';

    post(route('movies.store', $invalidMovie))
        ->assertRedirect(route('movies.create'))
        ->assertSessionHasErrorsIn('genre_name');
    get(route('movies.create'))
        ->assertSeeText('The genre name field is required.');
    assertDatabaseCount('movies', 0);
    assertDatabaseCount('actor_movie', 0);
});

it('creates a new genre if the one passed does not exist in the database', function () {
    $validMovie = $this->validMovie;
    $validMovie['genre_name'] = 'Fantasy';

    post(route('movies.store', $validMovie))
        ->assertRedirect(route('movies.index'));
    assertDatabaseCount('movies', 1);
    assertDatabaseCount('actor_movie', 1);
    assertDatabaseHas('genres', ['name' => 'Fantasy']);
});

it('shows an error if the format is missing', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['format_name'] = '';

    post(route('movies.store', $invalidMovie))
        ->assertRedirect(route('movies.create'))
        ->assertSessionHasErrorsIn('format_name');
    get(route('movies.create'))
        ->assertSeeText('The format name field is required.');
    assertDatabaseCount('movies', 0);
    assertDatabaseCount('actor_movie', 0);
});

it('creates a new format if the one passed does not exist in the database', function () {
    $validMovie = $this->validMovie;
    $validMovie['format_name'] = 'Hardcover';

    post(route('movies.store', $validMovie))
        ->assertRedirect(route('movies.index'));
    assertDatabaseCount('movies', 1);
    assertDatabaseCount('actor_movie', 1);
    assertDatabaseHas('formats', ['name' => 'Hardcover']);
});

it('has the old values in the form if the validation fails', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['title'] = '';

    post(route('movies.store', $invalidMovie))
        ->assertRedirect(route('movies.create'))
        ->assertSessionHasErrorsIn('title');
    get(route('movies.create'))
        ->assertOk()
        ->assertSeeText('The title field is required.')
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'name' => 'release_year',
                'value' => $this->validMovie['release_year']
            ])
                ->containsInput([
                    'name' => 'runtime',
                    'value' => $this->validMovie['runtime']
                ])
                ->contains('textarea',[
                    'name' => 'blurb',
                    'value' => $this->validMovie['blurb']
                ])
                ->containsInput([
                    'name' => 'format_name',
                    'value' => $this->validMovie['format_name']
                ])
                ->containsInput([
                    'name' => 'actor[]',
                    'value' => $this->validMovie['actor'][0]
                ])
                ->containsInput([
                    'name' => 'genre_name',
                    'value' => $this->validMovie['genre_name']
                ]);
        });
});

it('has the old title value in the form if the validation fails', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['blurb'] = '';

    post(route('movies.store', $invalidMovie))
        ->assertRedirect(route('movies.create'))
        ->assertSessionHasErrorsIn('title');
    get(route('movies.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'name' => 'title',
                'value' => $this->validMovie['title']
            ]);
        });
});

it('can handle multiple actors when validation fails', function () {
    $actor = Actor::factory()->create();
    $invalidMovie = $this->validMovie;
    $invalidMovie['actor'] = [
        "{$this->actor->first_name} {$this->actor->last_name}",
        "{$actor->first_name} {$actor->last_name}",
    ];

    $invalidMovie['title'] = '';
    post(route('movies.store', $invalidMovie));
    get(route('movies.create'))
        ->assertOk()
        ->assertFormExists(fn (AssertForm $form) =>
        $form->containsInput([
            'name' => 'actor[]',
            'value' => $invalidMovie['actor'][0]
        ])
            ->containsInput([
                'name' => 'actor[]',
                'value' => $invalidMovie['actor'][1]
            ])
        );
});

