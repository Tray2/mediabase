<?php

namespace Tests\Feature\Http\RecordsControllerTest;

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Record;
use App\Models\Track;
use Tests\TestCase;

class RecordsControllerShowTest extends TestCase
{
    protected $record;

    protected function setUp(): void
    {
        parent::setUp();
        $artist = Artist::factory()->create([]);
        $format = Format::factory()->create([]);
        $genre = Genre::factory()->create([]);
        $this->record = Record::factory()->create([
            'artist_id' => $artist->id,
            'genre_id' => $genre->id,
            'format_id' => $format->id,
        ]);
    }

    /**
     * @test
     */
    public function anyone_can_view_information_about_a_record()
    {
        $artist = Artist::factory()->create([
            'name' => 'De La Soul'
        ]);
        $format = Format::factory()->create([
            'format' => 'Cd Single'
        ]);

        $genre = Genre::factory()->create([
            'genre' => 'Hip Hop'
        ]);

        $record = Record::factory()->create([
            'artist_id' => $artist->id,
            'title' => 'Ring Ring Ring (Ha Ha Hey)',
            'released' => 1991,
            'genre_id' => $genre->id,
            'format_id' => $format->id,
            'release_code' => 'M3445',
            'barcode' => '1332251464'
        ]);

        $tracks[] = Track::factory()->create([
            'track_no' => '01',
            'title' => 'Ring Ring Ring (Ha Ha Hey)',
            'mix' => 'Party Line Edit',
            'record_id' => $record->id
        ]);
        $tracks[] = Track::factory()->create([
            'track_no' => '02',
            'title' => 'Ring Ring Ring (Ha Ha Hey)',
            'mix' => 'Party Line Mix',
            'record_id' => $record->id
        ]);
        $tracks[] = Track::factory()->create([
            'track_no' => '03',
            'title' => 'Ring Ring Ring (Ha Ha Hey)',
            'mix' => 'Extended Decision U.S. Mix',
            'record_id' => $record->id
        ]);
        $tracks[] = Track::factory()->create([
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

    /**
    * @test
    */
    public function if_the_record_does_not_have_any_tracks_an_add_tracks_button_is_visible_to_logged_in_users()
    {
        $this->signIn();
        $response = $this->get('/records/' . $this->record->id);
        $response->assertSee('This records has no tracks yet');
        $response->assertSee('Add tracks');
    }

    /**
     * @test
     */
    public function the_add_tracks_button_is_not_visible_to_guests()
    {
        $response = $this->get('/records/' . $this->record->id);
        $response->assertSee('This records has no tracks yet');
        $response->assertDontSee('Add tracks');
    }

    /**
    * @test
    */
    public function if_the_record_has_tracks_the_edit_track_list_button_is_visible_to_logged_in_users()
    {
        Track::factory()->create([
            'record_id' => $this->record->id
        ]);

        $this->signIn();

        $response = $this->get('/records/' . $this->record->id);
        $response->assertDontSee('This records has no tracks yet');
        $response->assertSee('Edit track list');
    }

    /**
     * @test
     */
    public function the_edit_track_list_button_is_not_visible_to_guest()
    {
        Track::factory()->create([
            'record_id' => $this->record->id
        ]);
        $response = $this->get('/records/' . $this->record->id);
        $response->assertDontSee('This records has no tracks yet');
        $response->assertDontSee('Edit track list');
    }
}
