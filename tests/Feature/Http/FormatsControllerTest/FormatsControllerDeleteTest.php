<?php

namespace Tests\Feature\Http\FormatsControllerTest;

use App\Models\Format;
use Tests\TestCase;

class FormatsControllerDeleteTest extends TestCase
{
    /**
     * @test
     */
    public function after_deleting_an_format_the_user_is_redirected_to_the_formats_index_view_and_success_message_is_shown()
    {
        $this->signIn();

        $format = Format::factory()->create();

        $response = $this->delete('/formats/' . $format->id);

        $response->assertStatus(302);
        $response->assertLocation('/formats');

        $response = $this->get('/formats');
        $response->assertSee($format->format . ' successfully deleted.');
    }
}
