<?php

use App\Models\Book;
use App\Models\Game;
use App\Models\Movie;
use App\Models\Record;
use App\Models\User;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use function Pest\Laravel\get;

uses(FastRefreshDatabase::class);

beforeEach(function() {
   $this->user = User::factory()->create();
   $this->book = Book::factory()->create();
   $this->game = Game::factory()->create();
   $this->movie = Movie::factory()->create();
   $this->record = Record::factory()->create();
});

it('has a menu containing the different media types', function ($route, $media) {
    actingAs($this->user)
        ->get(route($route, $media))
        ->assertOk()
        ->assertSeeText([
            'Books',
            'Games',
            'Movies',
            'Records'
            ]);
})->with([
    ['books.index', 'book' => fn() => $this->book],
    ['books.create', 'book' => fn() => $this->book],
    ['books.show', 'book' => fn() => $this->book],
    ['books.edit', 'book' => fn() => $this->book],
    ['games.index', 'game' => fn() => $this->game],
    ['games.create', 'game' => fn() => $this->game],
    ['games.show', 'game' => fn() => $this->game],
    ['games.edit', 'game' => fn() => $this->game],
    ['movies.index', 'movie' => fn() => $this->movie],
    ['movies.create', 'movie' => fn() => $this->movie],
    ['movies.show', 'movie' => fn() => $this->movie],
    ['movies.edit', 'movie' => fn() => $this->movie],
    ['records.index', 'record' => fn() => $this->record],
    ['records.create', 'record' => fn() => $this->record],
    ['records.show', 'record' => fn() => $this->record],
    ['records.edit', 'record' => fn() => $this->record ],
]);

it('shows the log in link for a guest', function () {
    get('/records')
        ->assertOk()
        ->assertSeeText([
            "Log in"
        ]);
});

it('does not show a log in link for a logged in user' , function () {
    actingAs($this->user)
        ->get('/records')
        ->assertOk()
        ->assertDontSeeText([
            "Log in"
        ]);
});
