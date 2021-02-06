<?php

namespace Tests\Feature\Http\FormatsControllerTest;

use App\Models\Artist;
use App\Models\Author;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Record;
use Tests\TestCase;

class FormatsControllerIndexTest extends TestCase
{
    /**
     * @test
     */
    public function a_guest_can_list_all_formats()
    {
        $format1 = Format::factory()->create();
        $format2 = Format::factory()->create();

        $response = $this->get('/formats');
        $response->assertSee(e($format1->format));
        $response->assertSee(e($format2->format));
    }

    /**
     * @test
     */
    public function if_no_formats_exists_then_show_no_formats_found_is_shown_in_formats_index_view()
    {
        $response = $this->get('/formats');
        $response->assertStatus(200);
        $response->assertSee('No formats found');
    }

    /**
     * @test
     */
    public function a_user_sees_the_add_formats_button_while_a_guest_dont_see_it()
    {
        $guestResponse = $this->get('/formats');
        $this->signIn();
        $userResponse = $this->get('/formats');

        $guestResponse->assertDontSee('Add format');
        $userResponse->assertSee('Add format');
    }

    /**
     * @test
     */
    public function when_visiting_the_index_page_the_amount_of_books_in_the_format_is_shown()
    {
        Author::factory()->create();
        Format::factory()->create();
        Genre::factory()->create();
        Book::factory()->create();
        $response = $this->get('formats?type=books');
        $response->assertSee('<td>1</td>', false);
    }

    /**
    * @test
    */
    public function it_only_shows_the_formats_for_the_media_type_given_in_the_query_string()
    {
        Format::factory()->create(
            [
                'format' => 'Audio',
                'media_type_id' => env('BOOKS')
            ]
        );
        Format::factory()->create(
            [
                'format' => 'Lp',
                'media_type_id' => env('RECORDS')
            ]
        );


        Format::factory()->create();
        $response = $this->get('/formats?type=books');
        $response->assertSee('Audio');
        $response->assertDontSee('Lp');
    }

    /**
     * @test
     */
    public function when_visiting_the_index_page_the_amount_of_records_in_the_format_is_shown()
    {
        $this->withoutExceptionHandling();
        Artist::factory()->create();
        Format::factory()->create([
            'media_type_id' => env('RECORDS')
        ]);
        Genre::factory()->create();
        Record::factory()->create();
        $response = $this->get('formats?type=records');
        $response->assertSee('<td>1</td>', false);
    }

    /**
     * @test
     */
    public function when_type_is_present_in_the_query_string_only_formats_of_that_type_is_shown()
    {
        Format::factory()->create(['media_type_id' => env('BOOKS'), 'format' => 'Pocket']);
        Format::factory()->create(['media_type_id' => env('RECORDS'), 'format' => 'Lp']);
        Format::factory()->create(['media_type_id' => env('MOVIES'), 'format' => 'Dvd']);
        Format::factory()->create(['media_type_id' => env('GAMES'), 'format' => 'Dvd-Rom']);

        $this->get('/formats?type=BOOKS')->assertSee('Pocket')->assertDontSee(['Lp', 'Dvd', 'Dvd-Rom']);
    }

    /**
     * @test
     */
    public function when_type_is_set_in_the_query_string_the_table_header_shows_the_type()
    {
        Format::factory()->create(['media_type_id' => env('BOOKS'), 'format' => 'Pocket']);
        Format::factory()->create(['media_type_id' => env('RECORDS'), 'format' => 'Lp']);
        $this->get('/formats?type=BOOKS')->assertSeeTextInOrder(['Format', 'Books'], false);
        $this->get('/formats?type=RECORDS')->assertSeeTextInOrder(['Format', 'Records'], false);
    }

}
