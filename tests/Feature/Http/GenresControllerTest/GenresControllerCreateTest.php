<?php

namespace Tests\Feature\Http\GenresControllerTest;

use App\Models\Genre;
use Tests\TestCase;

class GenresControllerCreateTest extends TestCase
{
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
        $genre = Genre::factory()->make();

        $response = $this->post('/genres', $genre->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/genres');

        $response = $this->get('/genres');
        $response->assertSee(e($genre->genre) . ' successfully added.');
    }

    /**
     * @test
     */
    public function the_view_contains_a_list_of_available_media_types()
    {
        $this->signIn();
        $this->get('/genres/create')->assertSeeTextInOrder(
            [
                'Books',
                'Games',
                'Movies',
                'Records',
            ],
            false);
    }

    /**
    * @test
    */
    public function it_preselects_the_media_type_when_the_type_query_string_is_set()
    {
        $this->signIn();
        $this->get('/genres/create?type=BOOKS')->assertSeeInOrder(['selected', 'Books']);
        $this->get('/genres/create?type=RECORDS')->assertSeeInOrder(['selected', 'Records']);
        $this->get('/genres/create?type=MOVIES')->assertSeeInOrder(['selected', 'Movies']);
        $this->get('/genres/create?type=GAMES')->assertSeeInOrder(['selected', 'Games']);
    }

}
