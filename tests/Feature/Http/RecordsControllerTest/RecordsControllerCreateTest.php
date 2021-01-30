<?php

namespace Tests\Feature\Http\RecordsControllerTest;

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Record;

class RecordsControllerCreateTest extends RecordsControllerTestHelper
{
    /**
     * @test
     */
    public function users_can_visit_the_create_records_page()
    {
        Artist::factory()->create();
        $this->signIn();
        $response = $this->get('/records/create?artist_id=1');
        $response->assertSee('name="title"', false);
    }

    /**
     * @test
     */
    public function the_create_view_has_all_the_necessary_fields()
    {
        Artist::factory()->create();
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
    public function the_create_records_page_contains_all_genres()
    {
        Artist::factory()->create();
        $this->signIn();
        $genre1 = Genre::factory()->create(['media_type_id' => env('RECORDS')]);
        $genre2 = Genre::factory()->create(['media_type_id' => env('RECORDS')]);
        $genre3 = Genre::factory()->create(['media_type_id' => env('RECORDS')]);
        $genre4 = Genre::factory()->create(['media_type_id' => env('RECORDS')]);

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
        Artist::factory()->create();
        $this->signIn();
        $genreToSee = Genre::factory()->create([
            'genre' => 'Rap',
            'media_type_id' => env('RECORDS')
        ]);
        $genreNotToSee = Genre::factory()->create([
            'genre' => 'Fantasy',
            'media_type_id' => env('BOOKS')
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
        Artist::factory()->create();
        $this->signIn();
        $format1 = Format::factory()->create(['media_type_id' => env('RECORDS')]);
        $format2 = Format::factory()->create(['media_type_id' => env('RECORDS')]);
        $format3 = Format::factory()->create(['media_type_id' => env('RECORDS')]);
        $format4 = Format::factory()->create(['media_type_id' => env('RECORDS')]);

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
        Artist::factory()->create();
        $this->signIn();
        $formatToSee = Format::factory()->create([
            'format' => 'Lp',
            'media_type_id' => env('RECORDS')
        ]);
        $formatNotToSee = Format::factory()->create([
            'format' => 'Paperback',
            'media_type_id' => env('BOOKS')
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

    /**
     * @test
     */
    public function when_users_create_records_they_are_redirected_to_the_record_index_and_are_shown_a_success_message()
    {
        $this->createForeignKeys();
        $this->signIn();
        $record = Record::factory()->make();
        $record->artist_id = $this->artist[0]->id;

        $response = $this->post('/records', $record->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/records');

        $response = $this->get('/records');
        $response->assertSee(e($record->title) . ' successfully added.');
    }
}
