<?php

namespace Tests\Feature\Http\ArtistsControllerTest;

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Record;
use Tests\TestCase;

class ArtistsControllerIndexTest extends TestCase
{
    /**
     * @test
     */
    public function anyone_can_visit_the_artists_index_page()
    {
        $artist1 = Artist::factory()->create();
        $artist2 = Artist::factory()->create();

        $response = $this->get('/artists');
        $response->assertSee($artist1->name);
        $response->assertSee($artist2->name);
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
    public function users_see_the_add_artist_button_while_guests_dont_see_it_when_visiting_the_artist_index_view()
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
    public function when_visiting_the_index_page_the_amount_of_records_by_the_artist_is_shown()
    {
        $artist = Artist::factory()->create();
        Genre::factory()->create();
        Format::factory()->create();
        $response = $this->get('/artists');
        $response->assertSeeTextInOrder([$artist->name, '0'], false);
        Record::factory()->create();
        $response = $this->get('/artists');
        $response->assertSeeTextInOrder([$artist->name, '1'], false);
    }

    /**
    * @test
    */
    public function the_artists_in_the_list_is_sorted_in_alphabetical_order()
    {
        Artist::factory()->create(['name' => 'Run Dmc']);
        Artist::factory()->create(['name' => 'Ice-T']);
        Artist::factory()->create(['name' => 'Body Count']);
        Artist::factory()->create(['name' => 'N.W.A.']);
        Artist::factory()->create(['name' => '2Low']);
        $response = $this->get('/artists');
        $response->assertSeeTextInOrder(['2Low', 'Body Count', 'Ice-T', 'N.W.A.', 'Run Dmc']);
    }
}
