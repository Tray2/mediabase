<?php

namespace Tests\Feature\Http\BookReadsControllerTest;

use App\Author;
use App\AuthorBook;
use App\Book;
use App\BookCollection;
use App\BookRead;
use App\Format;
use App\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BookReadsControllerCreateTest extends TestCase
{
    use RefreshDatabase;

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
    public function a_user_can_mark_a_book_as_read()
    {
        $this->signIn();
        $book = factory(Book::class)->create();
        factory(AuthorBook::class)->create([
            'author_id' => $this->author->id,
            'book_id' => $book->id
        ]);
        factory(BookCollection::class)->create([
            'book_id' => $book->id,
            'user_id' => Auth::user()->id
        ]);

        $this->post('/books/read', ['book_id' => $book->id]);
        $response = $this->get('/books/' . $book->id);
        $response->assertSee(e($book->title) . ' marked as read.');
        $this->assertEquals(1, BookRead::count());
    }
}
