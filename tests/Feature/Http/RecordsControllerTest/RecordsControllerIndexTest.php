<?php

namespace Tests\Feature\Http\RecordsControllerTest;

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Record;
use Tests\TestCase;

class RecordsControllerIndexTest extends TestCase
{
    /**
     * @test
     */
    public function anyone_can_visit_the_index_page()
    {
        $artist = Artist::factory()->create();
        $genre = Genre::factory()->create();
        $format = Format::factory()->create();
        Record::factory()->create([
            'title' => 'Tougher Than Leather',
            'artist_id' => $artist->id,
            'format_id' => $format->id,
            'genre_id' => $genre->id
        ]);
        Record::factory()->create([
            'title' => 'Down With The King',
            'artist_id' => $artist->id,
            'format_id' => $format->id,
            'genre_id' => $genre->id
        ]);
        $response = $this->get('/records');
        $response->assertSee('Down With The King');
        $response->assertSee('Tougher Than Leather');
    }

}
