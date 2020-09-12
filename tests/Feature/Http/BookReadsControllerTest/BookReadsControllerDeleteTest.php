<?php

namespace Tests\Feature\Http\BookReadsControllerTest;

use Tests\TestCase;
use App\Models\Author;
use App\Models\AuthorBook;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use App\Models\BookRead;
use Illuminate\Support\Facades\Auth;

class BookReadsControllerDeleteTest extends TestCase
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
    public function a_user_can_mark_a_book_as_unread()
    {
        $this->signIn();
        Format::factory()->create();
        Genre::factory()->create();
        $book = Book::factory()->create();
        AuthorBook::factory()->create([
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
