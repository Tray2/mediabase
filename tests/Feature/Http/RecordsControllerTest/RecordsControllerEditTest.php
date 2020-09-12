<?php

namespace Tests\Feature\Http\RecordsControllerTest;

use App\Models\Record;
use Tests\TestCase;

class RecordsControllerEditTest extends TestCase
{
    /**
     * @test
     */
    public function users_can_visit_records_edit_page()
    {
        $record = Record::factory()->create();
        $this->signIn();
        $response = $this->get('/records/1/edit');
        $response->assertSee('value="' . $record->title . '"', false);
    }

}
