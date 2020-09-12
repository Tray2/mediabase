<?php

namespace Tests\Feature\Http\BookCollectionControllerTest;

use App\Models\Book;
use App\Models\BookCollection;
use App\Models\User;
use Illuminate\Support\Str;

class BookCollectionControllerIndexTest extends BookCollectionControllerTestHelper
{
    /**
     * @test
     */
    public function anyone_can_list_the_books_in_a_users_collection()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        BookCollection::factory()->create([
            'book_id' => $book->id,
            'user_id' => $user->id
        ]);

        $response = $this->get('/bookcollections/' . $user->id);

        $response->assertSee($book->title);
    }

    /**
     * @test
     */
    public function user_name_slug_can_be_used_instead_of_id_when_listing_a_users_collection()
    {
        $user = User::factory()->create([
            'name' => 'Kalle Svensson',
            'slug' => Str::slug('Kalle Svensson')
        ]);
        $book = Book::factory()->create();
        BookCollection::factory()->create([
            'book_id' => $book->id,
            'user_id' => $user->id
        ]);
        $response = $this->get('/bookcollections/' . Str::slug($user->name));
        $response->assertSee($book->title);
    }

}
