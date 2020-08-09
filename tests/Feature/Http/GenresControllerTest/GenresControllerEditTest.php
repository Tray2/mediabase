<?php

namespace Tests\Feature\Http\GenresControllerTest;

use App\Genre;
use Tests\TestCase;

class GenresControllerEditTest extends TestCase
{
    /**
     * @test
     */
    public function a_user_can_edit_an_genre()
    {
        $this->signIn();

        $genre = factory(Genre::class)->create([
            'genre' => 'Fantasy',
        ]);

        $response = $this->get('/genres/' . $genre->id . '/edit');

        $response->assertSee('name="genre"', false);
        $response->assertSee('name="id"', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee('name="_method"', false);
        $response->assertSee('value="PUT"', false);
        $response->assertSee('value="Fantasy"', false);
        $response->assertSee('value="' . $genre->id . '"', false);
        $response->assertSee('input type="submit" value="Update"', false);
    }
}
