<?php

namespace Tests\Feature\Http\AuthorsControllerTest;

use App\Author;
use Tests\TestCase;

class AuthorsControllerDeleteTest extends TestCase
{
    /**
     * @test
     */
    public function after_deleting_an_author_the_user_is_redirected_to_the_authors_index_view_and_success_message_is_shown()
    {
        $this->signIn();

        $author = factory(Author::class)->create();

        $response = $this->delete('/authors/' . $author->id);

        $response->assertStatus(302);
        $response->assertLocation('/authors');

        $response = $this->get('/authors');
        $response->assertSee($author->name . ' successfully deleted.');
    }
}
