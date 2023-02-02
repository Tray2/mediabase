<?php

use App\Models\Actor;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Movie;
use App\Models\User;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use Sinnbeck\DomAssertions\Asserts\AssertForm;

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
    $this->user = User::factory()->create();
    actingAs($this->user)->get(route('movies.edit', $this->movie));
});

it('updates a valid movie', function () {
    $validMovie = $this->validMovie;
    $validMovie['title'] = 'Some New Title';
    actingAs($this->user)->put(route('movies.update', $this->movie), $validMovie)
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

    actingAs($this->user)->put(route('movies.update', $this->movie), $validMovie)
        ->assertRedirect(route('movies.index'));
    assertDatabaseCount('movies', 1);
    assertDatabaseCount('actor_movie', 1);
});

it('creates a new actor if the one passed does not exist in the database', function () {
    $validMovie = $this->validMovie;
    $validMovie['actor'] = ['Robert Jordan'];

    actingAs($this->user)->put(route('movies.update', $this->movie), $validMovie)
        ->assertRedirect(route('movies.index'));
    assertDatabaseCount('movies', 1);
    assertDatabaseCount('actor_movie', 1);
    assertDatabaseHas('actors', ['last_name' => 'Jordan', 'first_name' => 'Robert']);
});

it('creates a new genre if the one passed does not exist in the database', function () {
    $validMovie = $this->validMovie;
    $validMovie['genre_name'] = 'Fantasy';

    actingAs($this->user)->put(route('movies.update', $this->movie), $validMovie)
        ->assertRedirect(route('movies.index'));
    assertDatabaseCount('movies', 1);
    assertDatabaseCount('actor_movie', 1);
    assertDatabaseHas('genres', ['name' => 'Fantasy']);
});

it('creates a new format if the one passed does not exist in the database', function () {
    $validMovie = $this->validMovie;
    $validMovie['format_name'] = 'Hardcover';

    actingAs($this->user)->put(route('movies.update', $this->movie), $validMovie)
        ->assertRedirect(route('movies.index'));
    assertDatabaseCount('movies', 1);
    assertDatabaseCount('actor_movie', 1);
    assertDatabaseHas('formats', ['name' => 'Hardcover']);
});

it('has the old values in the form if the validation fails', function () {
    $invalidMovie = $this->validMovie;
    $invalidMovie['title'] = '';
    actingAs($this->user)->put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('title');
    actingAs($this->user)->get(route('movies.edit', $this->movie))
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

    actingAs($this->user)->put(route('movies.update', $this->movie), $invalidMovie)
        ->assertRedirect(route('movies.edit', $this->movie))
        ->assertSessionHasErrorsIn('blurb');

    actingAs($this->user)->get(route('movies.create'))
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
    actingAs($this->user)->put(route('movies.update', $this->movie), $invalidMovie);
    actingAs($this->user)->get(route('movies.edit', $this->movie))
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
