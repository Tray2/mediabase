<?php

namespace Tests\Feature\Http\FormatsControllerTest;

use App\Models\Format;
use Tests\TestCase;

class FormatsControllerCreateTest extends TestCase
{
    /**
     * @test
     */
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
        $format = Format::factory()->make();

        $response = $this->post('/formats', $format->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/formats');

        $response = $this->get('/formats');
        $response->assertSee($format->format . ' successfully added.');
    }

    /**
    * @test
    */
    public function the_view_contains_a_list_of_available_media_types()
    {
        $this->signIn();
        $this->get('/formats/create')->assertSeeTextInOrder(
            [
                'Books',
                'Games',
                'Movies',
                'Records',
            ],
            false);
    }

    /**
     * @test
     */
    public function it_preselects_the_media_type_when_the_type_query_string_is_set()
    {
        $this->signIn();
        $this->get('/formats/create?type=BOOKS')->assertSeeInOrder(['selected', 'Books']);
        $this->get('/formats/create?type=RECORDS')->assertSeeInOrder(['selected', 'Records']);
        $this->get('/formats/create?type=MOVIES')->assertSeeInOrder(['selected', 'Movies']);
        $this->get('/formats/create?type=GAMES')->assertSeeInOrder(['selected', 'Games']);
    }


}
