<?php

namespace Tests\Feature\Http\BooksControllerTest;

use App\Models\Book;

class BooksControllerUpdateTest extends BooksControllerTestHelper
{
    /**
     * @test
     */
    public function when_users_update_a_book_they_are_redirected_to_the_book_index_and_are_shown_a_success_message()
    {
        $this->createForeignKeys();
        $this->signIn();
        $book = Book::factory()->create();
        $book->title = 'Kalle';

        $response = $this->patch('/books/' . $book->id, $book->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/books');

        $response = $this->get('/books');
        $response->assertSee(e($book->title) . ' successfully updated.');
    }
}
