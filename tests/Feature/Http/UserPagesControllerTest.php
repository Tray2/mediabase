<?php

namespace Tests\Feature\Http;

use App\Models\Author;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use App\Models\BookCollection;
use App\Models\BookRead;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class UserPagesControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Author::factory()->create();
        Format::factory()->create();
        Genre::factory()->create();
        Book::factory()->create();
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

        BookCollection::factory()->create(['book_id' => 1, 'user_id' => 1]);
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

