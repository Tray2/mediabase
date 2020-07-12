<?php

namespace Tests\Feature\Http;

use App\Artist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArtistsControllerDeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function after_deleting_an_artist_the_user_is_redirected_to_the_artists_index_view_and_success_message_is_shown()
    {
        $this->signIn();

        $artist = factory(Artist::class)->create();

        $response = $this->delete('/artists/' . $artist->id);

        $response->assertStatus(302);
        $response->assertLocation('/artists');

        $response = $this->get('/artists');
        $response->assertSee($artist->name . ' successfully deleted.');
    }
}
