<?php

namespace Tests\Feature\Http\ArtistsControllerTest;

use App\Artist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArtistsControllerEditTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function users_can_edit_an_artist()
    {
        $this->signIn();
        $artist = factory(Artist::class)->create();
        $response = $this->get('/artists/1/edit');
        $response->assertSee(e($artist->name));
    }
}
