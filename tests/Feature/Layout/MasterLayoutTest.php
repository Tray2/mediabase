<?php

use App\Models\Book;
use App\Models\Game;
use App\Models\Movie;
use App\Models\Record;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
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
        ->get(route($route, $this->$media))
        ->assertOk()
        ->assertSeeText([
            'Books',
            'Games',
            'Movies',
            'Records'
            ]);
})->with([
    ['books.index', 'book'],
    ['books.create', 'book'],
    ['books.show', 'book'],
    ['books.edit', 'book'],
    ['games.index', 'game'],
    ['games.create', 'game'],
    ['games.show', 'game'],
    ['games.edit', 'game'],
    ['movies.index', 'movie'],
    ['movies.create', 'movie'],
    ['movies.show', 'movie'],
    ['movies.edit', 'movie'],
    ['records.index', 'record'],
    ['records.create', 'record'],
    ['records.show', 'record'],
    ['records.edit', 'record'],
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
