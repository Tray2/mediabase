<?php

namespace Tests\Feature\Http;

use App\Format;
use App\Genre;
use App\Record;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Artist;

class ArtistsControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function anyone_can_visit_the_artists_index_page()
    {
        $this->withoutExceptionHandling();
        $artist1 = factory(Artist::class)->create();
        $artist2 = factory(Artist::class)->create();

        $response = $this->get('/artists');
        $response->assertSee($artist1->name);
        $response->assertSee($artist2->name);
    }


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
    public function anyone_can_visit_the_artists_show_page()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $artist = factory(Artist::class)->create();
        $response = $this->get('artists/' . $artist->id);
        $response->assertSee($artist->name);
    }


    /**
     * @test
     */
    public function users_can_store_artists()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $artist = factory(Artist::class)->make();
        $this->post('/artists', $artist->toArray());
        $this->assertDatabaseHas('artists', ['name' => $artist->name]);
    }


    /**
     * @test
     */
    public function users_can_update_an_artist()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $artist = factory(Artist::class)->create();
        $artist->name = 'Run Dmc';
        $this->patch('/artists/' . $artist->id, $artist->toArray());
        $this->assertDatabaseHas('artists', ['name' => $artist->name]);
    }


    /**
     * @test
     */
    public function users_can_edit_an_artist()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $artist = factory(Artist::class)->create();
        $response = $this->get('/artists/1/edit');
        $response->assertSee(e($artist->name));
    }

    /**
    * @test
    */
    public function if_there_are_no_artists_no_artists_to_display_is_shown()
    {
        $this->get('/artists')->assertSee('No artists to display');
    }

    /**
     * @test
     */
    public function users_see_the_add_aritst_button_while_guests_dont_see_it_when_visiting_the_artist_index_view()
    {
        $guestResponse = $this->get('/artists');
        $this->signIn();
        $userResponse = $this->get('/artists');

        $guestResponse->assertDontSee('Add artist');
        $userResponse->assertSee('Add artist');
    }

    /**
     * @test
     */
    public function users_sees_the_add_record_while_guests_dont_see_it_when_visiting_the_artist_show_view()
    {
        $artist = factory(Artist::class)->create();
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
        $this->withoutExceptionHandling();
        $artist = factory(Artist::class)->create();
        $response = $this->get('/artists/' . $artist->id);
        $response->assertSee('Artist has no records', false);
    }

    /**
     * @test
     */
    public function when_a_user_visits_the_artist_show_view_the_add_record_link_contains_the_artist_id_as_a_get_parameter()
    {
        $this->signIn();
        $artist = factory(Artist::class)->create();
        $response = $this->get('/artists/' . $artist->id);
        $response->assertSee('?artist_id=' . $artist->id);
    }

    /**
     * @test
     */
    public function slug_can_be_used_in_place_of_id_the_get_the_artist_for_the_show_view()
    {
        $artist = factory(Artist::class)->create();
        $response = $this->get('/artists/' . $artist->slug);
        $response->assertSee(e($artist->name));
    }

    /**
     * @test
     */
    public function when_visiting_the_index_page_the_amount_of_records_by_the_artist_is_shown()
    {
        $this->withoutExceptionHandling();
        factory(Artist::class)->create();
        factory(Format::class)->create();
        factory(Genre::class)->create();
        $response = $this->get('/artists');
        $response->assertSee('<td>0</td>', false);
        factory(Record::class)->create();
        $response = $this->get('/artists');
        $response->assertSee('<td>1</td>', false);
    }
}
