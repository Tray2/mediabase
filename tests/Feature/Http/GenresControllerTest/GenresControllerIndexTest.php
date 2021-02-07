<?php

namespace Tests\Feature\Http\GenresControllerTest;

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
}
