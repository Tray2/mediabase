<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Series;
use function Pest\Laravel\get;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;

uses(FastRefreshDatabase::class);

beforeEach(function () {
    $this->mediaTypeId = MediaType::query()
        ->where('name', 'book')
        ->value('id');
});

it('lists books', function () {
    $genre = Genre::factory()->create([
        'media_type_id' => $this->mediaTypeId,
    ]);
    $format = Format::factory()->create([
        'media_type_id' => $this->mediaTypeId,
    ]);
    $series = Series::factory()->create();
    $fields = ['title', 'published_year', 'part'];
    [$book1, $book2] = Book::factory()->count(2)->create([
        'genre_id' => $genre->id,
        'format_id' => $format->id,
        'series_id' => $series->id,
    ]);
    get(route('books.index'))
        ->assertOk()
        ->assertSeeText([
            ...$book1->only($fields),
            ...$book2->only($fields),
            $genre->name,
            $format->name,
            $series->name,
        ]);
});

it('sorts books by author', function () {
    Author::factory()
        ->count(2)
        ->sequence(
            ['first_name' => 'David', 'last_name' => 'Eddings'],
            ['first_name' => 'Sarah', 'last_name' => 'Ash']
        )->has(Book::factory())
        ->create();

    get(route('books.index'))
        ->assertOk()
        ->assertSeeTextInOrder([
            'Ash, Sarah',
            'Eddings, David',
        ]);
});

it('sorts books in the same series by part', function () {
    Author::factory()
        ->has(Book::factory()
        ->count(3)
        ->sequence(
            ['part' => 2, 'published_year' => 1990],
            ['part' => 1, 'published_year' => 1989],
            ['part' => 3, 'published_year' => 1991]
        )
       ->for(Series::factory([
           'name' => 'The Second Series',
       ]))
        )
        ->create();

    get(route('books.index'))
        ->assertOk()
        ->assertSeeTextInOrder([1989, 1990, 1991]);
});

it('sorts series of the same author by the published year of the first book in the series', function () {
    Author::factory()
        ->has(Book::factory()
            ->count(3)
            ->sequence(
                ['part' => 2, 'published_year' => 1971],
                ['part' => 1, 'published_year' => 1970],
                ['part' => 3, 'published_year' => 1972]
            )
            ->for(Series::factory([
                'name' => 'The Second Series',
            ]))
        )
        ->create([
            'first_name' => 'Ben',
            'last_name' => 'Something',
        ]);

    Author::factory()
        ->has(Book::factory()
            ->count(3)
            ->sequence(
                ['part' => 2, 'published_year' => 1970],
                ['part' => 1, 'published_year' => 1968],
                ['part' => 3, 'published_year' => 1971]
            )
            ->for(Series::factory([
                'name' => 'The First Series',
            ]))
        )
        ->create([
            'first_name' => 'Ben',
            'last_name' => 'Something',
        ]);

    get(route('books.index'))
        ->assertOk()
        ->assertSeeTextInOrder([
            '1968', 'The First Series',
            '1970', 'The First Series',
            '1971', 'The First Series',
            '1970', 'The Second Series',
            '1971', 'The Second Series',
            '1972', 'The Second Series',
        ]);
});

it('sorts a standalone book by the same author as a part of a Standalone series', function () {
    Author::factory()
        ->has(Book::factory()
            ->count(3)
            ->sequence(
                ['part' => 2, 'published_year' => 1971],
                ['part' => 1, 'published_year' => 1970],
                ['part' => 3, 'published_year' => 1972]
            )
            ->for(Series::factory(['name' => 'Second Series']))
        )
        ->create([
            'first_name' => 'Ben',
            'last_name' => 'Something',
        ]);

    Author::factory()
        ->has(Book::factory([
            'part' => null,
            'published_year' => 1971,
        ])
            ->for(Series::factory(['name' => 'Standalone']))
        )
        ->create([
            'first_name' => 'Ben',
            'last_name' => 'Something',
        ]);

    get(route('books.index'))
        ->assertOk()
        ->assertSeeTextInOrder([
            '1970', 'Second Series',
            '1971', 'Second Series',
            '1972', 'Second Series',
            '1971', 'Standalone',
        ]);
});

it('sorts books with multiple authors by the first author in alphabetical order', function () {
    [$author1, $author2] = Author::factory()
        ->count(2)
        ->sequence(
            ['first_name' => 'Robert', 'last_name' => 'Jordan'],
            ['first_name' => 'Brandon', 'last_name' => 'Sanderson']
        )
        ->create();

    $book = Book::factory()->create();
    $book->authors()->attach($author1);
    $book->authors()->attach($author2);

    get(route('books.index'))
        ->assertOk()
        ->assertSeeTextInOrder([
            'Jordan, Robert',
            'Sanderson, Brandon',
        ]);
});
