<?php

namespace Tests\Feature\Http\FormatsControllerTest;

use App\Models\Author;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
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
        $response = $this->get('formats');
        $response->assertSee('<td>1</td>', false);
    }
}
