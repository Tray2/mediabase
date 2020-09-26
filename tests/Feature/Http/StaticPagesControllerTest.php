<?php

namespace Tests\Feature\Http;

use App\Models\Artist;
use App\Models\Author;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Record;
use Tests\TestCase;

class StaticPagesControllerTest extends TestCase
{
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
    public function anyone_can_visit_the_start_page()
    {
        $response =$this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Welcome to Mediabase.');
    }

    /**
    * @test
    */
    public function logged_in_users_trying_to_visit_the_start_page_gets_redirected_to_their_dashboard()
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

        Author::factory()->create();
        Format::factory()->create();
        Genre::factory()->create();
        Book::factory()->create();

        $responseOneBook = $this->get('/');
        $responseOneBook->assertSee('Over 1 books');
    }

    /**
    * @test
    */
    public function the_start_page_displays_a_count_of_records_that_is_stored()
    {
        $responseNoRecords = $this->get('/');
        $responseNoRecords->assertSee('0 records');

        Artist::factory()->create();
        Format::factory()->create();
        Genre::factory()->create();
        Record::factory()->create();

        $responseOneRecord = $this->get('/');
        $responseOneRecord->assertSee('1 records');
    }
}
