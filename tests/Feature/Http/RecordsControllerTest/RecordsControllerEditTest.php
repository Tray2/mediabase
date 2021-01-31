<?php

namespace Tests\Feature\Http\RecordsControllerTest;

use App\Models\Format;
use App\Models\Genre;
use App\Models\Record;
use Tests\TestCase;

class RecordsControllerEditTest extends TestCase
{
    /**
     *  @test
     */
    public function users_can_edit_a_record()
    {
        $this->signIn();
        Genre::factory()->create(['media_type_id' => env('RECORDS')]);
        Format::factory()->create(['media_type_id' => env('RECORDS')]);
        $record = Record::factory()->create();

        $response = $this->get('/records/' . $record->id . '/edit');
        $response->assertSee('name="artist_id"', false);
        $response->assertSee('name="title"', false);
        $response->assertSee('name="released"', false);
        $response->assertSee('name="release_code"', false);
        $response->assertSee('name="barcode"', false);
        $response->assertSee('name="id', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee('name="_method"', false);

        $response->assertSee('value="PUT"', false);
        $response->assertSee('value="' .$record->title . '"', false);
        $response->assertSee('value="' . $record->series . '"', false);
        $response->assertSee('value="' . $record->part . '"', false);
        $response->assertSee('value="' . $record->isbn . '"', false);
        $response->assertSee('value="' . $record->released . '"', false);
        $response->assertSee('value="' . $record->reprinted . '"', false);
        $response->assertSee('value="' . $record->pages . '"', false);
        $response->assertSee('input type="submit" value="Update"', false);

        $response->assertSee('name="genre_id"', false);
        $response->assertSee('name="format_id"', false);
        $response->assertSee('<option value="1" selected>' . $record->genre->genre . '</option>', false);
        $response->assertSee('<option value="1" selected>' . $record->format->format . '</option>', false);
    }
}
