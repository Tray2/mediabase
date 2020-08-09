<?php

namespace Tests\Feature\Http\AuthorsControllerTest;

use App\Author;
use Tests\TestCase;

class AuthorsControllerCreateTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_create_authors()
    {
        $this->signIn();

        $response = $this->get('/authors/create');

        $response->assertSee('name="first_name"', false);
        $response->assertSee('name="last_name"', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee('input type="submit" value="Save"', false);
    }

    /**
     * @test
     */
    public function after_creating_an_author_the_user_is_redirected_to_the_authors_index_view_and_success_message_is_shown()
    {
        $this->signIn();
        $author = factory(Author::class)->make();

        $response = $this->post('/authors', $author->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/authors');

        $response = $this->get('/authors');
        $response->assertSee($author->name . ' successfully added.');
    }

}
