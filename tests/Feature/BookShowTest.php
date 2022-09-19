<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('it shows all the information about a book', function() {
   $book = Book::factory()
       ->has(Author::factory())
       ->for($genre = Genre::factory()->create())
       ->for($format = Format::factory()->create())
       ->for($series = Series::factory()->create())
       ->for($publisher = Publisher::factory()->create())
       ->create();
   $author = $book->authors->first();


    get(route('books.show', $book))
    ->assertOk()
    ->assertSeeText([
        $book->title,
        $book->isbn,
        $book->part,
        $book->blurb,
        $book->published_year,
        $author->first_name,
        $author->last_name,
        $genre->name,
        $format->name,
        $series->name,
        $publisher->name,
    ]);
});


