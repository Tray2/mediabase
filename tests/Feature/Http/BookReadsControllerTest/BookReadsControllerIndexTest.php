<?php

namespace Tests\Feature\Http\BookReadsControllerTest;

use App\Author;
use App\AuthorBook;
use App\Book;
use App\BookCollection;
use App\BookRead;
use App\Format;
use App\Genre;
use App\User;
use Tests\TestCase;

class BookReadsControllerIndexTest extends TestCase
{
    protected $author;

    protected function setUp(): void
    {
        parent::setUp();
        $this->author = factory(Author::class)->create();
        factory(Genre::class)->create();
        factory(Format::class)->create();
    }

    /**
     * @test
     */
    public function anyone_can_list_the_books_read_by_a_user()
    {
        $user = factory(User::class)->create();
        $book = factory(Book::class)->create();
        factory(AuthorBook::class)->create([
            'author_id' => $this->author->id,
            'book_id' => $book->id
        ]);
        factory(BookCollection::class)->create([
            'book_id' => $book->id,
            'user_id' => $user->id
        ]);

        factory(BookRead::class)->create([
            'book_id' => $book->id,
            'user_id' => $user->id
        ]);
        $response = $this->get('/books/read/' . $user->id);

        $response->assertSee($book->title);
    }

}
