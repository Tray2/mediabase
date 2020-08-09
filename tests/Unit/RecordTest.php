<?php

namespace Tests\Unit;

use App\Artist;
use App\Format;
use App\Genre;
use App\Record;
use Tests\TestCase;

class RecordTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_get_the_records_artist()
    {
        $artist = factory(Artist::class)->create([
            'name' => 'Run Dmc'
        ]);

        $record = factory(Record::class)->create([
            'artist_id' => $artist->id
        ]);

        $this->assertEquals('Run Dmc', $record->artist->name);
    }

    /**
    * @test
    */
    public function it_can_get_the_records_format()
    {
        factory(Artist::class)->create();
        $format = factory(Format::class)->create([
            'format' => 'Cd Single',
            'media_type_id' => env('RECORDS')
        ]);

        $record = factory(Record::class)->create([
            'format_id' => $format->id
        ]);

        $this->assertEquals('Cd Single', $record->format->format);
    }

    /**
    * @test
    */
    public function it_can_get_the_record_genre()
    {
        factory(Artist::class)->create();
        factory(Genre::class)->create(['media_type_id' => env('RECORDS')]);
        $genre = factory(Genre::class)->create([
            'genre' => 'Rap',
            'media_type_id' => env('RECORDS')
        ]);

        $record = factory(Record::class)->create([
            'genre_id' => $genre->id
        ]);

        $this->assertEquals('Rap', $record->genre->genre);
    }
}
