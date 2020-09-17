<?php

namespace Tests\Feature\Http\TracksControllerTest;

use Tests\TestCase;

class TracksControllerShowTest extends TestCase
{
    /**
     * @test
     */
    public function show_route_gives_a_405_error()
    {
        $this->get('/tracks/1')->assertStatus(405);
    }
}
