<?php

use App\Models\Actor;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use Carbon\Carbon;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use Sinnbeck\DomAssertions\Asserts\AssertForm;

uses(FastRefreshDatabase::class);

beforeEach(function () {
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

it('creates a new actor if the one passed does not exist in the database', function () {
    $validMovie = $this->validMovie;
    $validMovie['actor'] = ['Robert Jordan'];

    post(route('movies.store', $validMovie))
        ->assertRedirect(route('movies.index'));
    assertDatabaseCount('movies', 1);
    assertDatabaseCount('actor_movie', 1);
    assertDatabaseHas('actors', ['last_name' => 'Jordan', 'first_name' => 'Robert']);
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

    post(route('movies.store', $invalidMovie))
        ->assertRedirect(route('movies.create'))
        ->assertSessionHasErrorsIn('title');
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
    post(route('movies.store', $invalidMovie));
    get(route('movies.create'))
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
