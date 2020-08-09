<?php

namespace Tests\Feature\Http\GenresControllerTest;

use App\Genre;
use Tests\TestCase;

class GenresControllerDeleteTest extends TestCase
{
    /**
     * @test
     */
    public function after_deleting_an_genre_the_user_is_redirected_to_the_genres_index_view_and_success_message_is_shown()
    {
        $this->signIn();

        $genre = factory(Genre::class)->create();

        $response = $this->delete('/genres/' . $genre->id);

        $response->assertStatus(302);
        $response->assertLocation('/genres');

        $response = $this->get('/genres');
        $response->assertSee(e($genre->genre) . ' successfully deleted.');
    }
}
