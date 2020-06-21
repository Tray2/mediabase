<?php

namespace Tests\Feature\http;

use App\Author;
use App\Book;
use App\Format;
use App\Genre;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StaticPagesControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function anyone_can_visit_the_about_page()
    {
        $response =$this->get('/about');
        $response->assertStatus(200);
        $response->assertSee('About Mediabase.');
    }

    /**
    * @test
    */
    public function anyone_can_visit_the_contact_page()
    {
        $response =$this->get('/contact');
        $response->assertStatus(200);
        $response->assertSee('Contact Mediabase.');
    }

    /**
    * @test
    */
    public function guests_can_visit_the_start_page()
    {
        $response =$this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Welcome to Mediabase.');
    }

    /**
    * @test
    */
    public function users_trying_to_visit_the_start_page_gets_redirected_to_their_dashboard()
    {
        $this->signIn();
        $response = $this->get('/');
        $response->assertLocation('/home');
        $response->assertDontSee('Welcome to Mediabase.');
    }

    /**
    * @test
    */
    public function the_start_page_displays_a_count_of_books_that_is_stored()
    {
        $responseNoBooks = $this->get('/');
        $responseNoBooks->assertSee('Over 0 books');

        factory(Author::class)->create();
        factory(Format::class)->create();
        factory(Genre::class)->create();
        factory(Book::class)->create();

        $responseOneBook = $this->get('/');
        $responseOneBook->assertSee('Over 1 books');
    }
}
