<?php

namespace Tests\Feature\Http;

use App\Artist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArtistsControllerStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function users_can_store_artists()
    {
        $this->signIn();
        $artist = factory(Artist::class)->make();
        $this->post('/artists', $artist->toArray());
        $this->assertDatabaseHas('artists', ['name' => $artist->name]);
    }

}
