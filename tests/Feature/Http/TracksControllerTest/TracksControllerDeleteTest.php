<?php

namespace Tests\Feature\Http\TracksControllerTest;

use App\Models\Record;
use App\Models\Track;
use Tests\TestCase;

class TracksControllerDeleteTest extends TestCase
{
    /**
     * @test
     */
    public function users_can_delete_tracks()
    {
        $this->signIn();
        Record::factory()->create();
        Track::factory()->create();
        $this->assertEquals(1, Track::count());
        $this->delete('/tracks/1');
        $this->assertEquals(0, Track::count());
    }
}
