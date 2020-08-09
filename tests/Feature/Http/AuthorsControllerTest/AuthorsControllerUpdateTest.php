<?php

namespace Tests\Feature\Http\AuthorsControllerTest;

use App\Author;
use Tests\TestCase;

class AuthorsControllerUpdateTest extends TestCase
{
    /**
     * @test
     */
    public function after_updating_an_author_the_user_is_redirected_to_the_authors_index_view_and_success_message_is_shown()
    {
        $this->signIn();
        $author = factory(Author::class)->create();
        $author->first_name = 'Kalle';

        $response = $this->patch('/authors/' . $author->id, $author->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/authors');

        $response = $this->get('/authors');
        $response->assertSee($author->name . ' successfully updated.');
    }
}
