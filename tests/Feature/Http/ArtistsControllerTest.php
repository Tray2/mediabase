<?php

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Artist;

class ArtistsControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function anyone_can_visit_the_artists_index_page()
    {
        $this->withoutExceptionHandling();
        $artist1 = factory(Artist::class)->create();
        $artist2 = factory(Artist::class)->create();

        $response = $this->get('/artists');
        $response->assertSee($artist1->name);
        $response->assertSee($artist2->name);
    }


    /**
     * @test
     */
    public function a_user_can_visit_the_artists_create_page()
    {
        $this->signIn();
        $response = $this->get('/artists/create');
        $response->assertSee('name="name"', false);
    }

    /**
     * @test
     */
    public function anyone_can_visit_the_artists_show_page()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $artist = factory(Artist::class)->create();
        $response = $this->get('artists/' . $artist->id);
        $response->assertSee($artist->name);
    }


    /**
     * @test
     */
    public function users_can_store_artists()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $artist = factory(Artist::class)->make();
        $this->post('/artists', $artist->toArray());
        $this->assertDatabaseHas('artists', ['name' => $artist->name]);
    }


    /**
     * @test
     */
    public function users_can_update_an_artist()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $artist = factory(Artist::class)->create();
        $artist->name = 'Run Dmc';
        $this->patch('/artists/' . $artist->id, $artist->toArray());
        $this->assertDatabaseHas('artists', ['name' => $artist->name]);
    }


    /**
     * @test
     */
    public function users_can_edit_an_artist()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $artist = factory(Artist::class)->create();
        $response = $this->get('/artists/1/edit');
        $response->assertSee(e($artist->name));
    }
}
