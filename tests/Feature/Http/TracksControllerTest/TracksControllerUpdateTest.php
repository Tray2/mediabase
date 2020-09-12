<?php

namespace Tests\Feature\Http\TracksControllerTest;

use App\Models\Record;
use App\Models\Track;
use Tests\TestCase;

class TracksControllerUpdateTest extends TestCase
{
    /**
     * @test
     */
    public function users_can_visit_tracks_edit_page()
    {
        $record = Record::factory()->create();
        $track = Track::factory()->create([
            'record_id' => $record->id
        ]);
        $this->signIn();
        $response = $this->get('/tracks/1/edit');
        $response->assertSee('value="' . $track->title . '"', false);
    }
}
