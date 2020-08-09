<?php

namespace Tests\Feature\Http\RecordsControllerTest;

use App\Artist;
use App\Format;
use App\Genre;
use Tests\TestCase;

class RecordsControllerCreateTest extends TestCase
{
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
    public function the_create_records_page_contains_all_genres()
    {
        factory(Artist::class)->create();
        $this->signIn();
        $genre1 = factory(Genre::class)->create(['media_type_id' => env('RECORDS')]);
        $genre2 = factory(Genre::class)->create(['media_type_id' => env('RECORDS')]);
        $genre3 = factory(Genre::class)->create(['media_type_id' => env('RECORDS')]);
        $genre4 = factory(Genre::class)->create(['media_type_id' => env('RECORDS')]);

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
            'media_type_id' => env('RECORDS')
        ]);
        $genreNotToSee = factory(Genre::class)->create([
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
        factory(Artist::class)->create();
        $this->signIn();
        $format1 = factory(Format::class)->create(['media_type_id' => env('RECORDS')]);
        $format2 = factory(Format::class)->create(['media_type_id' => env('RECORDS')]);
        $format3 = factory(Format::class)->create(['media_type_id' => env('RECORDS')]);
        $format4 = factory(Format::class)->create(['media_type_id' => env('RECORDS')]);

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
            'media_type_id' => env('RECORDS')
        ]);
        $formatNotToSee = factory(Format::class)->create([
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

}
