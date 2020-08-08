<?php

namespace Tests\Feature\Http\RecordsControllerTest;

use App\Record;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecordsControllerDeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function users_can_delete_records()
    {
        $this->signIn();
        factory(Record::class)->create();
        $this->assertEquals(1, Record::count());
        $this->delete('/records/1');
        $this->assertEquals(0, Record::count());
    }
}
