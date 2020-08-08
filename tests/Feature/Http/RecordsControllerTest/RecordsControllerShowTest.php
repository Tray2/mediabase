<?php

namespace Tests\Feature\Http\RecordsControllerTest;

use App\Artist;
use App\Format;
use App\Genre;
use App\Record;
use App\Track;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecordsControllerShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function anyone_can_view_information_about_a_record()
    {
        $artist = factory(Artist::class)->create([
            'name' => 'De La Soul'
        ]);
        $format = factory(Format::class)->create([
            'format' => 'Cd Single'
        ]);

        $genre = factory(Genre::class)->create([
            'genre' => 'Hip Hop'
        ]);

        $record = factory(Record::class)->create([
            'artist_id' => $artist->id,
            'title' => 'Ring Ring Ring (Ha Ha Hey)',
            'released' => 1991,
            'genre_id' => $genre->id,
            'format_id' => $format->id,
            'release_code' => 'M3445',
            'barcode' => '1332251464'
        ]);

        $tracks[] = factory(Track::class)->create([
            'track_no' => '01',
            'title' => 'Ring Ring Ring (Ha Ha Hey)',
            'mix' => 'Party Line Edit',
            'record_id' => $record->id
        ]);
        $tracks[] = factory(Track::class)->create([
            'track_no' => '02',
            'title' => 'Ring Ring Ring (Ha Ha Hey)',
            'mix' => 'Party Line Mix',
            'record_id' => $record->id
        ]);
        $tracks[] = factory(Track::class)->create([
            'track_no' => '03',
            'title' => 'Ring Ring Ring (Ha Ha Hey)',
            'mix' => 'Extended Decision U.S. Mix',
            'record_id' => $record->id
        ]);
        $tracks[] = factory(Track::class)->create([
            'track_no' => '04',
            'title' => 'Piles And Piles Of Demo Tpesbi-Da Miles',
            'mix' => 'Conely Decision',
            'record_id' => $record->id
        ]);

        $response = $this->get('/records/' . $record->id);

        $response->assertSee($record->artist->name);
        $response->assertSee($record->title);
        $response->assertSee($record->released);
        $response->assertSee($record->genre->genre);
        $response->assertSee($record->format->format);
        $response->assertSee($record->release_code);
        $response->assertSee($record->barcode);
        $response->assertSeeInOrder([
            $tracks[0]->track_no,
            $tracks[0]->title,
            $tracks[0]->mix,
            $tracks[1]->track_no,
            $tracks[1]->title,
            $tracks[1]->mix,
            $tracks[2]->track_no,
            $tracks[2]->title,
            $tracks[2]->mix,
            $tracks[3]->track_no,
            $tracks[3]->title,
            $tracks[3]->mix,
        ]);
    }
}
