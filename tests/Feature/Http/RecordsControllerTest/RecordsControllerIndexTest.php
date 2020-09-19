<?php

namespace Tests\Feature\Http\RecordsControllerTest;

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Record;
use App\Models\Score;
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

    /**
     * @test
     */
    public function record_rating_is_shown_in_records_index_view()
    {
        Artist::factory()->create();
        Genre::factory()->create();
        Format::factory()->create();
        $record = Record::factory()->create();

        Score::factory()->create([
            'item_id' => $record->id,
            'media_type_id' => MediaType::where('media', 'Records')->pluck('id')->first(),
            'score' => '3'
        ]);

        $response = $this->get('/records');


        $response->assertSee('3.0/5.0', false);
    }

    /**
     * @test
     */
    public function if_no_books_exists_then_show_no_books_found_is_shown_in_books_index_view()
    {
        $response = $this->get('/records');
        $response->assertStatus(200);
        $response->assertSee('No records found');
    }

    /**
     * @test
     */
    public function only_users_can_see_the_add_record_button()
    {
        $guestResponse = $this->get('/records');
        $this->signIn();
        $userResponse = $this->get('/records');

        $guestResponse->assertDontSee('Add record');
        $userResponse->assertSee('Add record');
    }

}
