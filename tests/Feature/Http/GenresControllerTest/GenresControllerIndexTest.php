<?php

namespace Tests\Feature\Http\GenresControllerTest;

use App\Models\Author;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use Tests\TestCase;

class GenresControllerIndexTest extends TestCase
{
    /**
     * @test
     */
    public function a_guest_can_list_all_genres()
    {
        $genre1 = Genre::factory()->create(['media_type_id' => env('BOOKS')]);
        $genre2 = Genre::factory()->create(['media_type_id' => env('RECORDS')]);

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
        Author::factory()->create();
        Format::factory()->create();
        $genre = Genre::factory()->create(['media_type_id' => env('BOOKS')]);
        Book::factory()->create();
        $response = $this->get('genres?type=BOOKS');
        $response->assertSeeTextInOrder([$genre->genre, '1'], false);
    }

    /**
    * @test
    */
    public function when_type_is_present_in_the_query_string_only_genres_of_that_type_is_shown()
    {
        Genre::factory()->create(['media_type_id' => env('BOOKS'), 'genre' => 'Fantasy']);
        Genre::factory()->create(['media_type_id' => env('RECORDS'), 'genre' => 'Hip Hop']);
        Genre::factory()->create(['media_type_id' => env('MOVIES'), 'genre' => 'Action']);
        Genre::factory()->create(['media_type_id' => env('GAMES'), 'genre' => 'MMORPG']);

        $this->get('/genres?type=BOOKS')->assertSee('Fantasy')->assertDontSee(['Hip Hop', 'Action', 'MMORPG']);
    }

    /**
    * @test
    */
    public function when_type_is_set_in_the_query_string_the_table_header_shows_the_type()
    {
        Genre::factory()->create(['media_type_id' => env('BOOKS'), 'genre' => 'Fantasy']);
        Genre::factory()->create(['media_type_id' => env('RECORDS'), 'genre' => 'Hip Hop']);
        $this->get('/genres?type=BOOKS')->assertSeeTextInOrder(['Genre', 'Books'], false);
        $this->get('/genres?type=RECORDS')->assertSeeTextInOrder(['Genre', 'Records'], false);
    }
}
