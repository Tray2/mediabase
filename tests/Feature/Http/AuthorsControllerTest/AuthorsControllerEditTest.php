<?php

namespace Tests\Feature\Http\AuthorsControllerTest;

use App\Models\Author;
use Tests\TestCase;

class AuthorsControllerEditTest extends TestCase
{
    /**
     * @test
     */
    public function an_user_can_edit_an_author()
    {
        $this->signIn();

        $author = Author::factory()->create([
            'last_name' => 'Jordan',
            'first_name' => 'Robert'
        ]);

        $response = $this->get('/authors/' . $author->id . '/edit');

        $response->assertSee('name="first_name"', false);
        $response->assertSee('name="last_name"', false);
        $response->assertSee('name="id"', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee('name="_method"', false);
        $response->assertSee('value="Robert"', false);
        $response->assertSee('value="Jordan"', false);
        $response->assertSee('value="' . $author->id . '"', false);
    }

}
