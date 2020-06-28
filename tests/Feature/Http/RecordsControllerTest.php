<?php

namespace Tests\Feature\Http;

use App\Artist;
use App\Format;
use App\Genre;
use App\Record;
use App\Track;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecordsControllerTest extends TestCase
{
    use RefreshDatabase;

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

    /**
    * @test
    */
    public function users_can_visit_the_create_records_page()
    {
        factory(Artist::class)->create();
        $this->signIn();
        $response = $this->get('/records/create?artist_id=1');
        $response->assertSee('name="title"', false);
    }

    /**
    * @test
    */
    public function users_can_visit_records_edit_page()
    {
        $record = factory(Record::class)->create();
        $this->signIn();
        $response = $this->get('/records/1/edit');
        $response->assertSee('value="' . $record->title . '"', false);
    }

    /**
    * @test
    */
    public function users_can_update_a_record()
    {
        factory(Artist::class)->create();
        factory(Genre::class)->create();
        factory(Format::class)->create();
        $this->signIn();
        $record = factory(Record::class)->create([
            'title' => 'Original Title'
        ]);
        $record->title = 'Updated Title';
        $this->put('/records/' . $record->id, $record->toArray());
        $this->assertEquals(1, Record::where('title', 'Updated Title')->count());

    }

    /**
    * @test
    */
    public function guests_can_not_delete_a_record()
    {
        $response = $this->delete('/records/1');
        $response->assertLocation('login');
    }

    /**
    * @test
    */
    public function users_can_delete_records()
    {
        $this->signIn();
        factory(Record::class)->create();
        $this->assertEquals(1, Record::count());
        $this->delete('/records/1');
        $this->assertEquals(0, Record::count());
    }

    /**
    * @test
    */
    public function the_create_view_has_all_the_necessary_fields()
    {
        factory(Artist::class)->create();
        $this->signIn();
        $response = $this->get('/records/create?artist_id=1');
        $fields =[
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

    /**
    * @test
    */
    public function the_update_view_has_the_necessary_fields()
    {
        $this->signIn();
        factory(Record::class)->create();
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

    /**
    * @test
    */
    public function the_create_records_page_contains_all_genres()
    {
        factory(Artist::class)->create();
        $this->signIn();
        $genre1 = factory(Genre::class)->create(['type' => 'records']);
        $genre2 = factory(Genre::class)->create(['type' => 'records']);
        $genre3 = factory(Genre::class)->create(['type' => 'records']);
        $genre4 = factory(Genre::class)->create(['type' => 'records']);

        $response = $this->get('/records/create?artist_id=1');
        $response->assertSee($genre1->genre);
        $response->assertSee($genre2->genre);
        $response->assertSee($genre3->genre);
        $response->assertSee($genre4->genre);
    }

    /**
    * @test
    */
    public function the_create_records_page_does_only_contain_record_genres()
    {
        factory(Artist::class)->create();
        $this->signIn();
        $genreToSee = factory(Genre::class)->create([
            'genre' => 'Rap',
            'type' => 'records'
        ]);
        $genreNotToSee = factory(Genre::class)->create([
            'genre' => 'Fantasy',
            'type' => 'books'
        ]);

        $response = $this->get('/records/create?artist_id=1');

        $response->assertSee($genreToSee->genre);
        $response->assertDontSee($genreNotToSee->genre);
    }

    /**
     * @test
     */
    public function the_create_records_page_contains_all_formats()
    {
        factory(Artist::class)->create();
        $this->signIn();
        $format1 = factory(Format::class)->create(['type' => 'records']);
        $format2 = factory(Format::class)->create(['type' => 'records']);
        $format3 = factory(Format::class)->create(['type' => 'records']);
        $format4 = factory(Format::class)->create(['type' => 'records']);

        $response = $this->get('/records/create?artist_id=1');
        $response->assertSee($format1->format);
        $response->assertSee($format2->format);
        $response->assertSee($format3->format);
        $response->assertSee($format4->format);
    }

    /**
     * @test
     */
    public function the_create_records_page_does_only_contain_record_formats()
    {
        factory(Artist::class)->create();
        $this->signIn();
        $formatToSee = factory(Format::class)->create([
            'format' => 'Lp',
            'type' => 'records'
        ]);
        $formatNotToSee = factory(Format::class)->create([
            'format' => 'Paperback',
            'type' => 'books'
        ]);

        $response = $this->get('/records/create?artist_id=1');

        $response->assertSee($formatToSee->format);
        $response->assertDontSee($formatNotToSee->format);
    }
    /**
     * @test
     */
    public function users_trying_to_access_records_create_without_artist_id_are_redirected_to_the_artist_index_and_message_is_shown()
    {
        $this->signIn();
        $response = $this->get('/records/create');
        $response->assertLocation('/artists');
        $response = $this->get('/artists');
        $response->assertSee('You must specify an artist.', false);
    }

}
