<?php

namespace Tests\Feature\Http\GenresControllerTest;

use App\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenresControllerUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function after_updating_an_genre_the_user_is_redirected_to_the_genres_index_view_and_success_message_is_shown()
    {
        $this->signIn();
        $genre = factory(Genre::class)->create();
        $genre->genre = 'Kalle';

        $response = $this->patch('/genres/' . $genre->id, $genre->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/genres');

        $response = $this->get('/genres');
        $response->assertSee(e($genre->genre) . ' successfully updated.');
    }

}
