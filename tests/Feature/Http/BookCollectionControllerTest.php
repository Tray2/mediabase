<?php

namespace Tests\Feature\Http;

use App\Author;
use App\Book;
use App\BookCollection;
use App\Format;
use App\Genre;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookCollectionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        factory(Author::class)->create();
        factory(Genre::class)->create();
        factory(Format::class)->create();
    }

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
