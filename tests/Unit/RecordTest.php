<?php

namespace Tests\Unit;

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Record;
use App\Models\Score;
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

    /**
     * @test
     */
    public function it_gets_the_average_of_the_scores_for_the_record()
    {
        $this->withoutExceptionHandling();
        $record = Record::factory()->create();
        $mediaType = MediaType::where('media', 'Records')->pluck('id')->first();
        Score::factory()->create([
            'item_id' => $record->id,
            'media_type_id' => $mediaType,
            'score' => '4'
        ]);

        Score::factory()->create([
            'item_id' => $record->id,
            'media_type_id' => $mediaType,
            'score' => '2'
        ]);

        $this->assertEquals(3, $record->score);
    }

}
