<?php

namespace Tests\Feature\Http\GenresControllerTest;

use App\Author;
use App\Book;
use App\Format;
use App\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenresControllerIndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_can_list_all_genres()
    {
        $genre1 = factory(Genre::class)->create(['type' => 'book']);
        $genre2 = factory(Genre::class)->create(['type' => 'record']);

        $response = $this->get('/genres');
        $response->assertSee(e($genre1->genre));
        $response->assertSee(e($genre2->genre));
        $response->assertSee('book');
        $response->assertSee('record');
    }

    /**
     * @test
     */
    public function if_no_genres_exists_then_show_no_genres_found_is_shown_in_genres_index_view()
    {
        $response = $this->get('/genres');
        $response->assertStatus(200);
        $response->assertSee('No genres found');
    }

    /**
     * @test
     */
    public function a_user_sees_the_add_genres_button_while_a_guest_dont_see_it()
    {
        $guestResponse = $this->get('/genres');
        $this->signIn();
        $userResponse = $this->get('/genres');

        $guestResponse->assertDontSee('Add genre');
        $userResponse->assertSee('Add genre');
    }

    /**
     * @test
     */
    public function when_visiting_the_index_page_the_amount_of_books_in_the_genre_is_shown()
    {
        factory(Author::class)->create();
        factory(Format::class)->create();
        factory(Genre::class)->create(['type' => 'books']);
        factory(Book::class)->create();
        $response = $this->get('genres');
        $response->assertSee('<td>1</td>', false);
    }
}
