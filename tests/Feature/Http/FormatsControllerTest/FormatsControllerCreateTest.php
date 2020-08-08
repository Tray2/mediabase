<?php

namespace Tests\Feature\Http\FormatsControllerTest;

use App\Format;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormatsControllerCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_format()
    {
        $this->signIn();

        $response = $this->get('/formats/create');

        $response->assertSee('name="format"', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee('input type="submit" value="Save"', false);
    }

    /**
     * @test
     */
    public function after_creating_an_format_the_user_is_redirected_to_the_formats_index_view_and_success_message_is_shown()
    {
        $this->signIn();
        $format = factory(Format::class)->make();

        $response = $this->post('/formats', $format->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/formats');

        $response = $this->get('/formats');
        $response->assertSee($format->format . ' successfully added.');
    }

}
