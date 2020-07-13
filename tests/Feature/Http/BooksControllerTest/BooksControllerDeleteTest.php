<?php

namespace Tests\Feature\Http\BooksControllerTest;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BooksControllerDeleteTest extends BooksControllerTestHelper
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function when_users_delete_a_book_they_are_redirected_to_the_book_index_and_are_shown_a_success_message()
    {
        $this->createForeignKeys();
        $this->signIn();

        $book = factory(Book::class)->create();

        $response = $this->delete('/books/' . $book->id);

        $response->assertStatus(302);
        $response->assertLocation('/books');

        $response = $this->get('/books');
        $response->assertSee(e($book->title) . ' successfully deleted.');
    }

}
