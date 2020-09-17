<?php

namespace Tests\Feature\Http\TracksControllerTest;

use Tests\TestCase;

class TracksControllerIndexTest extends TestCase
{
    /**
     * @test
     */
    public function index_route_gives_http_error_405()
    {
        $this->get('/tracks')->assertStatus(405);
    }

}
