<?php

use App\Models\Author;
use App\Models\Book;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\delete;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;

uses(FastRefreshDatabase::class);

it('deletes a book', function () {
    $book = Book::factory()->create();
    $book->authors()->attach(Author::factory()->create());
    assertDatabaseCount(Book::class, 1);
    assertDatabaseCount('author_book', 1);

    delete(route('books.delete', $book))
        ->assertRedirect(route('books.index'));

    assertDatabaseCount(Book::class, 0);
    assertDatabaseCount('author_book', 0);
});
