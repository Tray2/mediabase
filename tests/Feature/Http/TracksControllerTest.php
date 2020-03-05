<?php

namespace Tests\Feature\Http;

use App\Record;
use App\Track;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TracksControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function index_and_show_routes_gives_http_error_405()
    {
        $this->get('/tracks')->assertStatus(405);
        $this->get('/tracks/1')->assertStatus(405);
    }

    /**
     * @test
     */
    public function users_can_visit_the_create_tracks_page()
    {
        $this->signIn();
        $response = $this->get('/tracks/create');
        $response->assertSee('name="title"', false);
    }

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
