<?php

namespace Tests\Feature\Http\BookReadsControllerTest;

use App\Models\Author;
use App\Models\AuthorBook;
use App\Models\Book;
use App\Models\BookCollection;
use App\Models\BookRead;
use App\Models\Format;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BookReadsControllerCreateTest extends TestCase
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
    public function a_user_can_mark_a_book_as_read()
    {
        $this->signIn();
        $book = Book::factory()->create();
        AuthorBook::factory()->create([
            'author_id' => $this->author->id,
            'book_id' => $book->id
        ]);
        BookCollection::factory()->create([
            'book_id' => $book->id,
            'user_id' => Auth::user()->id
        ]);

        $this->post('/books/read', ['book_id' => $book->id]);
        $response = $this->get('/books/' . $book->id);
        $response->assertSee(e($book->title) . ' marked as read.');
        $this->assertEquals(1, BookRead::count());
    }
}
