<?php

namespace Tests\Unit\Validation;

use App\Models\BookCollection;
use Tests\TestCase;

class BookCollectionValidationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->signIn();
    }

    /**
    * @test
    */
    public function the_user_id_must_exist_in_users()
    {
        $bookCollection = BookCollection::factory()->make([
            'book_id' => 1,
            'user_id' => 10
        ]);

        $response = $this->post('/bookcollections', $bookCollection->toArray());
        $response->assertSessionHasErrors('user_id');
    }

    /**
    * @test
    */
    public function the_book_id_must_exist_in_books()
    {
        $bookCollection = BookCollection::factory()->make([
            'book_id' => 1,
            'user_id' => 1
        ]);

        $response = $this->post('/bookcollections', $bookCollection->toArray());
        $response->assertSessionHasErrors('book_id');
    }
}
