<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use function Pest\Laravel\assertDatabaseCount;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('deletes a book', function () {
    $book = Book::factory()->create();
    $book->authors()->attach(Author::factory()->create());
    assertDatabaseCount(Book::class, 1);
    assertDatabaseCount('author_book', 1);

    actingAs($this->user)->delete(route('books.delete', $book))
        ->assertRedirect(route('books.index'));

    assertDatabaseCount(Book::class, 0);
    assertDatabaseCount('author_book', 0);
});
