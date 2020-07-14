<?php

namespace Tests\Feature\Http\BookCollectionControllerTest;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class BookCollectionControllerCreateTest extends BookCollectionControllerTestHelper
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function users_can_add_books_to_their_collection()
    {
        $this->signIn();
        $book = factory(Book::class)->create();
        $response = $this->post('/bookcollections', ['book_id' => $book->id, 'user_id' => Auth::user()->id]);
        $response->assertLocation('/books');
        $response = $this->get('/books');
        $response->assertSee($book->title . ' successfully added to collection.');
    }

}
