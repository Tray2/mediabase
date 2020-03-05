<?php

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Author;
use App\AuthorBook;
use App\Book;
use App\Format;
use App\Genre;
use App\BookCollection;
use App\BookRead;
use Illuminate\Support\Facades\Auth;

class BookReadsControllerTest extends TestCase
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

    /**
    * @test
    */
    public function a_user_can_mark_a_book_as_unread()
    {
        $this->signIn();
        factory(Genre::class)->create();
        factory(Format::class)->create();
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
