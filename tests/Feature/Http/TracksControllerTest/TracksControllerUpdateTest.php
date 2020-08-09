<?php

namespace Tests\Feature\Http\TracksControllerTest;

use App\Record;
use App\Track;
use Tests\TestCase;

class TracksControllerUpdateTest extends TestCase
{
    /**
     * @test
     */
    public function users_can_visit_tracks_edit_page()
    {
        $this->withoutExceptionHandling();
        $record = factory(Record::class)->create();
        $track = factory(Track::class)->create([
            'record_id' => $record->id
        ]);
        $this->signIn();
        $response = $this->get('/tracks/1/edit');
        $response->assertSee('value="' . $track->title . '"', false);
    }
}
