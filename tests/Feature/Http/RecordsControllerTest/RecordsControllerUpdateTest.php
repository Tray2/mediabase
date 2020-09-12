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

    /**
     * @test
     */
    public function the_update_view_has_the_necessary_fields()
    {
        $this->signIn();
        Record::factory()->create();
        $response = $this->get('/records/1/edit');
        $fields =[
            'name="_method" value="patch"',
            'name="id"',
            'name="title"',
            'name="artist_id"',
            'name="_token"',
            'name="genre_id"',
            'name="format_id"',
            'name="released"',
            'name="release_code"',
            'name="spine_code"'
        ];

        foreach($fields as $field) {
            $response->assertSee($field, false);
        }
    }
}
