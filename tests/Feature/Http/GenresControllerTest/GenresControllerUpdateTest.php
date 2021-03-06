<?php

namespace Tests\Feature\Http\GenresControllerTest;

use App\Models\Genre;
use Tests\TestCase;

class GenresControllerUpdateTest extends TestCase
{
    /**
     * @test
     */
    public function after_updating_an_genre_the_user_is_redirected_to_the_genres_index_view_and_success_message_is_shown()
    {
        $this->signIn();
        $genre = Genre::factory()->create();
        $genre->genre = 'Kalle';

        $response = $this->patch('/genres/' . $genre->id, $genre->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/genres');

        $response = $this->get('/genres');
        $response->assertSee(e($genre->genre) . ' successfully updated.');
    }

    /**
     * @test
     */
    public function the_view_contains_a_list_of_available_media_types()
    {
        Genre::factory()->create();

        $this->signIn();
        $this->get('/genres/1/edit')->assertSeeInOrder(
            [
                '<option value="1">Books</option>',
                '<option value="2">Games</option>',
                '<option value="3">Movies</option>',
                '<option value="4">Records</option>',
            ],
            false);
    }
}
