<?php

namespace Tests\Feature\Http\ArtistsControllerTest;

use App\Models\Artist;
use Tests\TestCase;

class ArtistsControllerShowTest extends TestCase
{
    /**
     * @test
     */
    public function anyone_can_visit_the_artists_show_page()
    {
        $this->signIn();
        $artist = Artist::factory()->create();
        $response = $this->get('artists/' . $artist->id);
        $response->assertSee($artist->name);
    }

    /**
     * @test
     */
    public function users_sees_the_add_record_while_guests_dont_see_it_when_visiting_the_artist_show_view()
    {
        $artist = Artist::factory()->create();
        $responseGuest = $this->get('/artists/' . $artist->id);
        $this->signIn();
        $responseUser = $this->get('/artists/' . $artist->id);
        $responseGuest->assertDontSee('Add record');
        $responseUser->assertSee('Add record');
    }

    /**
     * @test
     */
    public function when_visiting_an_artist_that_has_no_records_the_message_artist_has_no_records_is_shown()
    {
        $artist = Artist::factory()->create();
        $response = $this->get('/artists/' . $artist->id);
        $response->assertSee('Artist has no records', false);
    }

    /**
     * @test
     */
    public function when_a_user_visits_the_artist_show_view_the_add_record_link_contains_the_artist_id_as_a_get_parameter()
    {
        $this->signIn();
        $artist = Artist::factory()->create();
        $response = $this->get('/artists/' . $artist->id);
        $response->assertSee('?artist_id=' . $artist->id);
    }

    /**
     * @test
     */
    public function slug_can_be_used_in_place_of_id_the_get_the_artist_for_the_show_view()
    {
        $artist = Artist::factory()->create();
        $response = $this->get('/artists/' . $artist->slug);
        $response->assertSee($artist->name);
    }
}
