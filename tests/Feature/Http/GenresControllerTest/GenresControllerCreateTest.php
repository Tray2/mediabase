<?php

namespace Tests\Feature\Http\GenresControllerTest;

use App\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenresControllerCreateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     * */
    public function a_user_can_create_a_genre()
    {
        $this->signIn();

        $response = $this->get('/genres/create');

        $response->assertSee('name="genre"', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee('input type="submit" value="Save"', false);
    }

    /**
     * @test
     */
    public function after_creating_an_genre_the_user_is_redirected_to_the_genres_index_view_and_success_message_is_shown()
    {
        $this->signIn();
        $genre = factory(Genre::class)->make();

        $response = $this->post('/genres', $genre->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/genres');

        $response = $this->get('/genres');
        $response->assertSee(e($genre->genre) . ' successfully added.');
    }
}
