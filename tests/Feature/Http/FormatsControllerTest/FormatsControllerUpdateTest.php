<?php

namespace Tests\Feature\Http\FormatsControllerTest;

use App\Format;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FormatsControllerUpdateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function after_updating_an_format_the_user_is_redirected_to_the_formats_index_view_and_success_message_is_shown()
    {
        $this->signIn();
        $format = factory(Format::class)->create();
        $format->format = 'Kalle';

        $response = $this->patch('/formats/' . $format->id, $format->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/formats');

        $response = $this->get('/formats');
        $response->assertSee($format->format . ' successfully updated.');
    }
}
