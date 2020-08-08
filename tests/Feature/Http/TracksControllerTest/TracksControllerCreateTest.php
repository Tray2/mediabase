<?php

namespace Tests\Feature\Http\TracksControllerTest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TracksControllerCreateTest extends TestCase
{
    /**
     * @test
     */
    public function users_can_visit_the_create_tracks_page()
    {
        $this->signIn();
        $response = $this->get('/tracks/create');
        $response->assertSee('name="title"', false);
    }
}
