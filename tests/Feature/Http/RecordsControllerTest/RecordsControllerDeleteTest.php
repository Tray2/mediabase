<?php

namespace Tests\Feature\Http\RecordsControllerTest;

use App\Models\Record;
use Tests\TestCase;

class RecordsControllerDeleteTest extends TestCase
{
    /**
     * @test
     */
    public function users_can_delete_records()
    {
        $this->signIn();
        Record::factory()->create();
        $this->assertEquals(1, Record::count());
        $this->delete('/records/1');
        $this->assertEquals(0, Record::count());
    }
}
