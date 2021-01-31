<?php

namespace Tests\Feature\Http\ArtistsControllerTest;

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Record;
use Tests\TestCase;

class ArtistsControllerShowTest extends TestCase
{
    protected $artist;
    protected function setUp(): void
    {
        parent::setUp();
        $this->artist = Artist::factory()->create();
        Format::factory()->create();
        Genre::factory()->create();
    }

    /**
     * @test
     */
    public function anyone_can_visit_the_artists_show_page()
    {
        $this->signIn();
        $response = $this->get('artists/' . $this->artist->id);
        $response->assertSee($this->artist->name);
    }

    /**
     * @test
     */
    public function users_sees_the_add_record_while_guests_dont_see_it_when_visiting_the_artist_show_view()
    {
        $responseGuest = $this->get('/artists/' . $this->artist->id);
        $this->signIn();
        $responseUser = $this->get('/artists/' . $this->artist->id);
        $responseGuest->assertDontSee('Add record');
        $responseUser->assertSee('Add record');
    }

    /**
     * @test
     */
    public function when_visiting_an_artist_that_has_no_records_the_message_artist_has_no_records_is_shown()
    {
        $response = $this->get('/artists/' . $this->artist->id);
        $response->assertSee('Artist has no records', false);
    }

    /**
     * @test
     */
    public function when_a_user_visits_the_artist_show_view_the_add_record_link_contains_the_artist_id_as_a_get_parameter()
    {
        $this->signIn();
        $response = $this->get('/artists/' . $this->artist->id);
        $response->assertSee('?artist_id=' . $this->artist->id);
    }

    /**
     * @test
     */
    public function slug_can_be_used_in_place_of_id_the_get_the_artist_for_the_show_view()
    {
        $response = $this->get('/artists/' . $this->artist->slug);
        $response->assertSee($this->artist->name);
    }

    /**
    * @test
    */
    public function the_artist_show_view_displays_a_list_of_records_by_the_artist()
    {
        $record = Record::factory()->create();
        $response = $this->get('/artists/' . $this->artist->slug);
        $response->assertSee($record->title);
    }

    /**
    * @test
    */
    public function the_list_of_records_is_sorted_by_release_year()
    {
        Record::factory()->create(['released' => '1981', 'title' => 'Rockbox']);
        Record::factory()->create(['released' => '1985', 'title' => 'Mary Mary']);
        Record::factory()->create(['released' => '1980', 'title' => 'Walk This Way']);
        Record::factory()->create(['released' => '1983', 'title' => 'Down With The King']);

        $response = $this->get('/artists/' . $this->artist->slug);
        $response->assertSeeTextInOrder(['Walk This Way', 'Rockbox', 'Down With The King', 'Mary Mary']);
    }
}
