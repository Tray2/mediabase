<?php

namespace Tests\Feature\Http\ArtistsControllerTest;

use App\Artist;
use Tests\TestCase;

class ArtistsControllerCreateTest extends TestCase
{
    /**
     * @test
     */
    public function a_user_can_visit_the_artists_create_page()
    {
        $this->signIn();
        $response = $this->get('/artists/create');
        $response->assertSee('name="name"', false);
    }

    /**
     * @test
     */
    public function after_creating_an_artist_the_user_is_redirected_to_the_artists_index_view_and_success_message_is_shown()
    {
        $this->signIn();
        $artist = factory(Artist::class)->make();

        $response = $this->post('/artists', $artist->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/artists');

        $response = $this->get('/artists');
        $response->assertSee($artist->name . ' successfully added.');
    }

}
