<?php

namespace Tests\Feature\Http\RecordsControllerTest;

use App\Record;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecordsControllerEditTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function users_can_visit_records_edit_page()
    {
        $record = factory(Record::class)->create();
        $this->signIn();
        $response = $this->get('/records/1/edit');
        $response->assertSee('value="' . $record->title . '"', false);
    }

}
