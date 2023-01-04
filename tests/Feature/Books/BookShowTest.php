<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Publisher;
use App\Models\Series;
use function Pest\Laravel\get;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;

uses(FastRefreshDatabase::class);

beforeEach(function () {
    $this->mediaTypeId = MediaType::query()
        ->where('name', 'book')
        ->value('id');
});

it('it shows all the information about a book', function () {
    $book = Book::factory()
        ->has(Author::factory())
        ->for($genre = Genre::factory()->create([
            'media_type_id' => $this->mediaTypeId,
        ]))
        ->for($format = Format::factory()->create([
            'media_type_id' => $this->mediaTypeId,
        ]))
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
