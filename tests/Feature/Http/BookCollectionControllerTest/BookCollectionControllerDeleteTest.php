<?php

namespace Tests\Feature\Http\BookCollectionControllerTest;

use App\Book;
use App\BookCollection;
use Illuminate\Support\Facades\Auth;

class BookCollectionControllerDeleteTest extends BookCollectionControllerTestHelper
{
    /**
     * @test
     */
    public function users_can_remove_books_from_their_collections()
    {
        $this->signIn();
        $book1 = factory(Book::class)->create();
        $book2 = factory(Book::class)->create();
        factory(BookCollection::class)->create(['book_id' => $book1->id, 'user_id' => Auth::user()->id]);
        factory(BookCollection::class)->create(['book_id' => $book2->id, 'user_id' => Auth::user()->id]);

        $response = $this->delete('/bookcollections/' . $book2->id);
        $response->assertLocation('/books');
        $response = $this->get('/books');
        $response->assertSee($book2->title . ' successfully removed from collection.');
    }

}
