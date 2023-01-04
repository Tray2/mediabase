<?php

use App\Models\Actor;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Movie;
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
        ->where('name', 'movie')
        ->value('id');
    $this->actor = Actor::factory()->create();
    $this->genre = Genre::factory()->create([
        'media_type_id' => $mediaTypeId,
    ]);
    $this->format = Format::factory()->create([
        'media_type_id' => $mediaTypeId,
    ]);
    $this->movie = Movie::factory()->create([
        'title' => 'Some Title',
        'release_year' => 1984,
        'blurb' => 'Some boring text',
        'runtime' => 94,
    ]);
    $this->validMovie = array_merge($this->movie->toArray(), [
        'actor' => ["{$this->actor->first_name} {$this->actor->last_name}"],
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
    ]);
    get(route('movies.edit', $this->movie));
});

it('updates a valid movie', function () {
    $validMovie = $this->validMovie;
    $validMovie['title'] = 'Some New Title';
    put(route('movies.update', $this->movie), $validMovie)
        ->assertRedirect(route('movies.index'));
    assertDatabaseHas('movies', ['title' => 'Some New Title']);
    assertDatabaseCount('actor_movie', 1);
    assertDatabaseCount('movies', 1);
});

it('can update a movie with multiple actors into on actor', function () {
    $this->movie->actors()->attach(Actor::factory()->create());
    $this->movie->actors()->attach(Actor::factory()->create());
    assertDatabaseCount('actor_movie', 2);

    $validMovie = $this->validMovie;
    $validMovie['actor'] = [
        "{$this->actor->first_name} {$this->actor->last_name}",
    ];

    put(route('movies.update', $this->movie), $validMovie)
        ->assertRedirect(route('movies.index'));
    assertDatabaseCount('movies', 1);
    assertDatabaseCount('actor_movie', 1);
});

it('redirects and shows an error if the title is missing', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['title'] = '';

    put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('title');
    get(route('movies.edit', $this->movie))
        ->assertSeeText('The title field is required.');
});

it('redirects and shows an error if the release year is missing', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['release_year'] = '';

    put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('release_year');
    get(route('movies.edit', $this->movie))
        ->assertSeeText('The release year field is required.');
});

it('shows an error if the release year is not numeric', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['release_year'] = 'Nineteen Eighty Four';

    put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('release_year');
    get(route('movies.edit', $this->movie))
        ->assertSeeText('The release year must be a number.');
});

it('shows an error if the release year is less than four digits', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['release_year'] = 123;

    put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('release_year');
    get(route('movies.edit', $this->movie))
        ->assertSeeText('The release year must have at least 4 digits.');
});

it('shows an error if the release year is more than four digits', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['release_year'] = 12345;

    put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('release_year');
    get(route('movies.edit', $this->movie))
        ->assertSeeText('The release year must not have more than 4 digits.');
});

it('shows an error if the release year is more than a year into the future', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['release_year'] = Carbon::now()->addYear(2)->year;

    put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('release_year');
    get(route('movies.edit', $this->movie))
        ->assertSeeText('The release year must be between 1800 and '.Carbon::now()->addYear(1)->year.'.');
});

it('shows an error if the runtime is missing', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['runtime'] = '';

    put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('runtime_year');
    get(route('movies.edit', $this->movie))
        ->assertSeeText('The runtime field is required.');
});

it('shows an error if the runtime is not numeric', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['runtime'] = 'Ninty three';

    put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('runtime');
    get(route('movies.edit', $this->movie))
        ->assertSeeText('The runtime must be a number.');
});

it('shows an error if the runtime is less than 2 digits', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['runtime'] = 1;

    put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('runtime');
    get(route('movies.edit', $this->movie))
        ->assertSeeText('The runtime must have at least 2 digits.');
});

it('shows an error if the runtime is more than 3 digits', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['runtime'] = 4444;

    put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('runtime');
    get(route('movies.edit', $this->movie))
        ->assertSeeText('The runtime must not have more than 3 digits.');
});

it('shows an error if the blurb is missing', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['blurb'] = '';

    put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('release_year');
    get(route('movies.edit', $this->movie))
        ->assertSeeText('The blurb field is required.');
});

it('shows an error if the blurb word count is less than three', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['blurb'] = 'This';

    put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('release_year');
    get(route('movies.edit', $this->movie))
        ->assertSeeText('The blurb must be at least 3 words.');
});

it('shows an error if the actor is missing', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['actor'] = '';

    put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('actor');
    get(route('movies.edit', $this->movie))
        ->assertSeeText('The actor field is required.');
});

it('creates a new actor if the one passed does not exist in the database', function () {
    $validMovie = $this->validMovie;
    $validMovie['actor'] = ['Robert Jordan'];

    put(route('movies.update', $this->movie), $validMovie)
        ->assertRedirect(route('movies.index'));
    assertDatabaseCount('movies', 1);
    assertDatabaseCount('actor_movie', 1);
    assertDatabaseHas('actors', ['last_name' => 'Jordan', 'first_name' => 'Robert']);
});

it('shows an error if the genre is missing', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['genre_name'] = '';

    put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('genre_name');
    get(route('movies.edit', $this->movie))
        ->assertSeeText('The genre name field is required.');
});

it('creates a new genre if the one passed does not exist in the database', function () {
    $validMovie = $this->validMovie;
    $validMovie['genre_name'] = 'Fantasy';

    put(route('movies.update', $this->movie), $validMovie)
        ->assertRedirect(route('movies.index'));
    assertDatabaseCount('movies', 1);
    assertDatabaseCount('actor_movie', 1);
    assertDatabaseHas('genres', ['name' => 'Fantasy']);
});

it('shows an error if the format is missing', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['format_name'] = '';

    put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('format_name');
    get(route('movies.edit', $this->movie))
        ->assertSeeText('The format name field is required.');
});

it('creates a new format if the one passed does not exist in the database', function () {
    $validMovie = $this->validMovie;
    $validMovie['format_name'] = 'Hardcover';

    put(route('movies.update', $this->movie), $validMovie)
        ->assertRedirect(route('movies.index'));
    assertDatabaseCount('movies', 1);
    assertDatabaseCount('actor_movie', 1);
    assertDatabaseHas('formats', ['name' => 'Hardcover']);
});

it('has the old values in the form if the validation fails', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['title'] = '';
    put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('title');
    get(route('movies.edit', $this->movie))
        ->assertOk()
        ->assertSeeText('The title field is required.')
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'name' => 'release_year',
                'value' => $this->validMovie['release_year'],
            ])
                ->containsInput([
                    'name' => 'runtime',
                    'value' => $this->validMovie['runtime'],
                ])
                ->contains('textarea', [
                    'name' => 'blurb',
                    'value' => $this->validMovie['blurb'],
                ])
                ->containsInput([
                    'name' => 'format_name',
                    'value' => $this->validMovie['format_name'],
                ])
                ->containsInput([
                    'name' => 'actor[]',
                    'value' => $this->validMovie['actor'][0],
                ])
                ->containsInput([
                    'name' => 'genre_name',
                    'value' => $this->validMovie['genre_name'],
                ]);
        });
});

it('has the old title value in the form if the validation fails', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['blurb'] = '';

    put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('blurb');

    get(route('movies.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'name' => 'title',
                'value' => $this->validMovie['title'],
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
    put(route('movies.update', $this->movie), $invalidMovie);
    get(route('movies.edit', $this->movie))
        ->assertOk()
        ->assertFormExists(fn (AssertForm $form) => $form->containsInput([
            'name' => 'actor[]',
            'value' => $invalidMovie['actor'][0],
        ])
            ->containsInput([
                'name' => 'actor[]',
                'value' => $invalidMovie['actor'][1],
            ])
        );
});
