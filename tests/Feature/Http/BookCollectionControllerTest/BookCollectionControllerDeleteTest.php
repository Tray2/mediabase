<?php

namespace Tests\Feature\Http\BookCollectionControllerTest;

use App\Models\Book;
use App\Models\BookCollection;
use Illuminate\Support\Facades\Auth;

class BookCollectionControllerDeleteTest extends BookCollectionControllerTestHelper
{
    /**
     * @test
     */
    public function users_can_remove_books_from_their_collections()
    {
        $this->signIn();
        $book1 = Book::factory()->create();
        $book2 = Book::factory()->create();
        BookCollection::factory()->create(['book_id' => $book1->id, 'user_id' => Auth::user()->id]);
        BookCollection::factory()->create(['book_id' => $book2->id, 'user_id' => Auth::user()->id]);

        $response = $this->delete('/bookcollections/' . $book2->id);
        $response->assertLocation('/books');
        $response = $this->get('/books');
        $response->assertSee($book2->title . ' successfully removed from collection.');
    }

}
