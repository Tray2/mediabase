<?php

namespace Tests\Feature\Http\FormatsControllerTest;

use App\Format;
use Tests\TestCase;

class FormatsControllerEditTest extends TestCase
{
    /** @test */
    public function a_user_can_edit_a_format()
    {
        $this->signIn();

        $format = factory(Format::class)->create([
            'Format' => 'Paperback',
        ]);

        $response = $this->get('/formats/' . $format->id . '/edit');

        $response->assertSee('name="format"', false);
        $response->assertSee('name="id"', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee('name="_method"', false);
        $response->assertSee('value="PUT"', false);
        $response->assertSee('value="Paperback"', false);
        $response->assertSee('value="' . $format->id . '"', false);
        $response->assertSee('input type="submit" value="Update"', false);
    }
}
