<?php

namespace Tests\Unit;

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Record;
use Tests\TestCase;

class RecordTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_get_the_records_artist()
    {
        $artist = Artist::factory()->create([
            'name' => 'Run Dmc'
        ]);

        $record = Record::factory()->make([
            'artist_id' => $artist->id
        ]);

        $this->assertEquals('Run Dmc', $record->artist->name);
    }

    /**
    * @test
    */
    public function it_can_get_the_records_format()
    {
        Artist::factory()->create();
        $format = Format::factory()->create([
            'format' => 'Cd Single',
            'media_type_id' => env('RECORDS')
        ]);

        $record = Record::factory()->make([
            'format_id' => $format->id
        ]);

        $this->assertEquals('Cd Single', $record->format->format);
    }

    /**
    * @test
    */
    public function it_can_get_the_record_genre()
    {
        Artist::factory()->create();
        Genre::factory()->create(['media_type_id' => env('RECORDS')]);
        $genre = Genre::factory()->create([
            'genre' => 'Rap',
            'media_type_id' => env('RECORDS')
        ]);

        $record = Record::factory()->make([
            'genre_id' => $genre->id
        ]);

        $this->assertEquals('Rap', $record->genre->genre);
    }
}
