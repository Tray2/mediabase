<?php

/** @noinspection NullPointerExceptionInspection */

use App\Models\Author;
use App\Models\Book;
use App\Models\BookShowView;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Series;
use function Pest\Laravel\get;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;

uses(FastRefreshDatabase::class);

it('shows a list of books in the same series excluding the one being showed', function () {
    $bookId = MediaType::query()->where('name', 'book')->value('id');
    $seriesId = Series::factory()->create()->id;
    Author::factory()
        ->has(Book::factory(['series_id' => $seriesId])
            ->count(5)
            ->sequence(
                ['part' => 1],
                ['part' => 2],
                ['part' => 3],
                ['part' => 4],
                ['part' => 5]
            )
            ->for(Format::factory(['media_type_id' => $bookId]))
            ->for(Genre::factory(['media_type_id' => $bookId]))
        )->create();
    $bookId = Book::query()->where('part', 2)->value('id');
    $books = Book::query()->whereNot('part', 2)->orderBy('part')->get();

    get(route('books.show', $bookId))
        ->assertOk()
        ->assertSeeInOrder([
            $books[0]->title,
            $books[1]->title,
            $books[2]->title,
            $books[3]->title,
        ]);
});

it('shows a list of all books by the author excluding'
    .'the ones in the same series and the one being shown', function () {
        $bookId = MediaType::query()->where('name', 'book')->value('id');
        $seriesId = Series::factory()->create(['name' => 'Standalone'])->id;
        Author::factory()
            ->has(Book::factory(['series_id' => $seriesId])
                ->count(5)
                ->sequence(
                    ['published_year' => 1991, 'part' => null],
                    ['published_year' => 1990, 'part' => null],
                    ['published_year' => 1992, 'part' => null],
                    ['published_year' => 1989, 'part' => null],
                    ['published_year' => 1994, 'part' => null]
                )
                ->for(Format::factory(['media_type_id' => $bookId]))
                ->for(Genre::factory(['media_type_id' => $bookId]))
            )->create();
        $bookId = Book::query()->where('published_year', 1990)->value('id');
        $books = Book::query()->whereNot('published_year', 1990)->orderBy('published_year')->get();

        get(route('books.show', $bookId))
            ->assertOk()
            ->assertSeeInOrder([
                $books[0]->title,
                $books[1]->title,
                $books[2]->title,
                $books[3]->title,
            ]);
    });

it('does not show books by another author', function () {
    $bookId = MediaType::query()->where('name', 'book')->value('id');
    $author1 = Author::factory()
        ->has(Book::factory()
            ->for(Format::factory(['media_type_id' => $bookId]))
            ->for(Genre::factory(['media_type_id' => $bookId]))
        )
        ->create();
    $author2 = Author::factory()
        ->has(Book::factory()
            ->for(Format::factory(['media_type_id' => $bookId]))
            ->for(Genre::factory(['media_type_id' => $bookId]))
        )
        ->create();

    $book1 = BookShowView::query()
        ->where('author_id', $author1->id)
        ->first();

    $book2 = BookShowView::query()
        ->where('author_id', $author2->id)
        ->first();

    get(route('books.show', $book1))
        ->assertOk()
        ->assertSee([
            $book1->title,
        ])
        ->assertDontSee([
            $book2->title,
        ]);
});

it('shows books from all authors of a book', function () {
    $bookId = MediaType::query()->where('name', 'book')->value('id');

    [$author1, $author2] = Author::factory()
        ->count(2)
        ->has(Book::factory()
            ->count(3)
            ->for(Format::factory(['media_type_id' => $bookId]))
            ->for(Genre::factory(['media_type_id' => $bookId]))
        )
        ->create();

    $book = Book::factory()
        ->for(Format::factory(['media_type_id' => $bookId]))
        ->for(Genre::factory(['media_type_id' => $bookId]))
        ->create();

    $author1Books = $author1->books()->orderBy('published_year')->pluck('title')->toArray();
    $author2Books = $author2->books()->orderBy('published_year')->pluck('title')->toArray();
    $book->authors()->attach([
        $author1->id,
        $author2->id,
    ]);

    get(route('books.show', $book))
        ->assertOk()
        ->assertSeeInOrder($author1Books)
        ->assertSeeInOrder($author2Books);
});
