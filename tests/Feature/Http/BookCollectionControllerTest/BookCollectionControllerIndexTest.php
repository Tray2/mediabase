<?php

namespace Tests\Feature\Http\BookCollectionControllerTest;

use App\Book;
use App\BookCollection;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class BookCollectionControllerIndexTest extends BookCollectionControllerTestHelper
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function anyone_can_list_the_books_in_a_users_collection()
    {
        $user = factory(User::class)->create();
        $book = factory(Book::class)->create();
        factory(BookCollection::class)->create([
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
        $user = factory(User::class)->create([
            'name' => 'Kalle Svensson',
            'slug' => Str::slug('Kalle Svensson')
        ]);
        $book = factory(Book::class)->create();
        factory(BookCollection::class)->create([
            'book_id' => $book->id,
            'user_id' => $user->id
        ]);
        $response = $this->get('/bookcollections/' . Str::slug($user->name));
        $response->assertSee($book->title);
    }

}
