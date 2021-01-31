<?php

namespace Tests\Feature\Http\RecordsControllerTest;

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Record;
use Tests\TestCase;

class RecordsControllerUpdateTest extends TestCase
{
    /**
     * @test
     */
    public function users_can_update_a_record()
    {
        Artist::factory()->create();
        Genre::factory()->create();
        Format::factory()->create();
        $this->signIn();
        $record = Record::factory()->create([
            'title' => 'Original Title'
        ]);
        $record->title = 'Updated Title';
        $this->put('/records/' . $record->id, $record->toArray());
        $this->assertEquals(1, Record::where('title', 'Updated Title')->count());
    }
}
