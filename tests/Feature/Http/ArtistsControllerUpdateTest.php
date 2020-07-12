<?php

namespace Tests\Feature\Http;

use App\Artist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArtistsControllerUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function users_can_update_an_artist()
    {
        $this->signIn();
        $artist = factory(Artist::class)->create();
        $artist->name = 'Run Dmc';
        $this->patch('/artists/' . $artist->id, $artist->toArray());
        $this->assertDatabaseHas('artists', ['name' => $artist->name]);
    }

    /**
     * @test
     */
    public function after_updating_an_artist_the_user_is_redirected_to_the_artist_index_view_and_success_message_is_shown()
    {
        $this->signIn();
        $artist = factory(Artist::class)->create();
        $artist->name = 'Kalle';

        $response = $this->patch('/artists/' . $artist->id, $artist->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/artists');

        $response = $this->get('/artists');
        $response->assertSee($artist->name . ' successfully updated.');
    }

}
