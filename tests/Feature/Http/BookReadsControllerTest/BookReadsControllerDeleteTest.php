<?php

namespace Tests\Feature\Http\BookReadsControllerTest;

use Tests\TestCase;
use App\Author;
use App\AuthorBook;
use App\Book;
use App\Format;
use App\Genre;
use App\BookRead;
use Illuminate\Support\Facades\Auth;

class BookReadsControllerDeleteTest extends TestCase
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
    public function a_user_can_mark_a_book_as_unread()
    {
        $this->signIn();
        factory(Format::class)->create();
        factory(Genre::class)->create();
        $book = factory(Book::class)->create();
        factory(AuthorBook::class)->create([
            'author_id' => $this->author->id,
            'book_id' => $book->id
        ]);

        BookRead::create(['book_id' => $book->id, 'user_id' => Auth::user()->id]);
        $this->delete('/books/read/' . $book->id);
        $response = $this->get('/books/' . $book->id);
        $response->assertSee(e($book->title) . ' marked as unread.');
        $this->assertEquals(0, BookRead::count());
    }
}
