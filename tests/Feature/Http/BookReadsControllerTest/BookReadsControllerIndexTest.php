<?php

namespace Tests\Feature\Http\BookReadsControllerTest;

use App\Models\Author;
use App\Models\AuthorBook;
use App\Models\Book;
use App\Models\BookCollection;
use App\Models\BookRead;
use App\Models\Format;
use App\Models\Genre;
use App\Models\User;
use Tests\TestCase;

class BookReadsControllerIndexTest extends TestCase
{
    protected $author;

    protected function setUp(): void
    {
        parent::setUp();
        $this->author = Author::factory()->create();
        Genre::factory()->create();
        Format::factory()->create();
    }

    /**
     * @test
     */
    public function anyone_can_list_the_books_read_by_a_user()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        AuthorBook::factory()->create([
            'author_id' => $this->author->id,
            'book_id' => $book->id
        ]);
        BookCollection::factory()->create([
            'book_id' => $book->id,
            'user_id' => $user->id
        ]);

        BookRead::factory()->create([
            'book_id' => $book->id,
            'user_id' => $user->id
        ]);
        $response = $this->get('/books/read/' . $user->id);

        $response->assertSee($book->title);
    }

}
