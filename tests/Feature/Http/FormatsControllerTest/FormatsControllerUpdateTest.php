<?php

namespace Tests\Feature\Http\FormatsControllerTest;

use App\Models\Format;
use Tests\TestCase;

class FormatsControllerUpdateTest extends TestCase
{
    /**
     * @test
     */
    public function after_updating_an_format_the_user_is_redirected_to_the_formats_index_view_and_success_message_is_shown()
    {
        $this->signIn();
        $format = Format::factory()->create();
        $format->format = 'Kalle';

        $response = $this->patch('/formats/' . $format->id, $format->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/formats');

        $response = $this->get('/formats');
        $response->assertSee($format->format . ' successfully updated.');
    }

    /**
     * @test
     */
    public function the_view_contains_a_list_of_available_media_types()
    {
        Format::factory()->create();

        $this->signIn();
        $this->get('/formats/1/edit')->assertSeeInOrder(
            [
                '<option value="1">Books</option>',
                '<option value="2">Games</option>',
                '<option value="3">Movies</option>',
                '<option value="4">Records</option>',
            ],
            false);
    }
}
