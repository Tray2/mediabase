<?php

namespace Tests\Feature\Http;

use App\Author;
use App\Book;
use App\Format;
use App\Genre;
use App\BookCollection;
use App\BookRead;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class UserPagesControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        factory(Author::class)->create();
        factory(Format::class)->create();
        factory(Genre::class)->create();
        factory(Book::class)->create();
    }

    /**
    * @test
    */
    public function users_can_visit_their_dashboard()
    {
        $this->signIn();
        $response = $this->get('/home');
        $response->assertStatus(200);
        $response->assertSee(Auth::user()->name);
    }

    /**
    * @test
    */
    public function users_see_how_many_books_they_have_in_their_collection()
    {
        $this->signIn();
        $response = $this->get('/home');
        $response->assertSee('>0</a>', false);

        factory(BookCollection::class)->create(['book_id' => 1, 'user_id' => 1]);
        $response = $this->get('/home');
        $response->assertSee('>1</a>', false);
    }

    /**
    * @test
    */
    public function users_see_how_many_books_theyve_marked_as_read()
    {
        $this->signIn();
        $response = $this->get('/home');
        $response->assertSee('Books Read: 0');
        BookRead::create([
            'book_id' => 1,
            'user_id' => 1
        ]);

        $response = $this->get('/home');
        $response->assertSee('Books Read: 1');
    }
}

