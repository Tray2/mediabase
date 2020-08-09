<?php

namespace Tests\Feature\Http\RecordsControllerTest;

use App\Artist;
use App\Format;
use App\Genre;
use App\Record;
use Tests\TestCase;

class RecordsControllerIndexTest extends TestCase
{
    /**
     * @test
     */
    public function anyone_can_visit_the_index_page()
    {
        $artist = factory(Artist::class)->create();
        $genre = factory(Genre::class)->create();
        $format = factory(Format::class)->create();
        factory(Record::class)->create([
            'title' => 'Tougher Than Leather',
            'artist_id' => $artist->id,
            'format_id' => $format->id,
            'genre_id' => $genre->id
        ]);
        factory(Record::class)->create([
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
