<?php

namespace Tests\Feature\Http;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Format;
use App\Author;
use App\Genre;
use App\Book;

class FormatsControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_can_list_all_formats()
    {
        $format1 = factory(Format::class)->create();
        $format2 = factory(Format::class)->create();

        $response = $this->get('/formats');
        $response->assertSee(e($format1->format));
        $response->assertSee(e($format2->format));
    }

    /** @test */
    public function a_guest_can_visit_a_format_and_see_all_the_books_belonging_to_it()
    {
        $this->withoutExceptionHandling();
        $format1 = factory(Format::class)->create();
        $format2 = factory(Format::class)->create();
        factory(Author::class)->create();
        factory(Genre::class)->create();
        $book1Format1 = factory(Book::class)->create(['title' => 'The Book Of Dreams', 'format_id' => $format1->id]);
        $book2Format1 = factory(Book::class)->create(['title' => 'The Book Of Fate', 'format_id' => $format1->id]);
        $book3Format2 = factory(Book::class)->create(['title' => 'The Book Of Lore', 'format_id' => $format2->id]);

        $response = $this->get('/formats/' . $format1->id);

        $response->assertSee(e($format1->format));
        $response->assertSee($book1Format1->title);
        $response->assertSee($book2Format1->title);
        $response->assertDontSee($book3Format2->title);
    }

    /** @test */
    public function a_user_can_edit_a_format()
    {
        $this->signIn();

        $format = factory(Format::class)->create([
          'Format' => 'Paperback',
      ]);

        $response = $this->get('/formats/' . $format->id . '/edit');

        $response->assertSee('name="format"', false);
        $response->assertSee('name="id"', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee('name="_method"', false);
        $response->assertSee('value="PUT"', false);
        $response->assertSee('value="Paperback"', false);
        $response->assertSee('value="' . $format->id . '"', false);
        $response->assertSee('input type="submit" value="Update"', false);
    }

    /** @test */
    public function a_user_can_create_a_format()
    {
        $this->signIn();

        $response = $this->get('/formats/create');

        $response->assertSee('name="format"', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee('input type="submit" value="Save"', false);
    }

    /**
    * @test
    */
    public function after_creating_an_format_the_user_is_redirected_to_the_formats_index_view_and_success_message_is_shown()
    {
        $this->signIn();
        $format = factory(Format::class)->make();

        $response = $this->post('/formats', $format->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/formats');

        $response = $this->get('/formats');
        $response->assertSee($format->format . ' successfully added.');
    }

    /**
    * @test
    */
    public function after_updating_an_format_the_user_is_redirected_to_the_formats_index_view_and_success_message_is_shown()
    {
        $this->signIn();
        $format = factory(Format::class)->create();
        $format->format = 'Kalle';

        $response = $this->patch('/formats/' . $format->id, $format->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/formats');

        $response = $this->get('/formats');
        $response->assertSee($format->format . ' successfully updated.');
    }

    /**
    * @test
    */
    public function after_deleting_an_format_the_user_is_redirected_to_the_formats_index_view_and_success_message_is_shown()
    {
        $this->signIn();

        $format = factory(format::class)->create();

        $response = $this->delete('/formats/' . $format->id);

        $response->assertStatus(302);
        $response->assertLocation('/formats');

        $response = $this->get('/formats');
        $response->assertSee($format->format . ' successfully deleted.');
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
        factory(Author::class)->create();
        factory(Format::class)->create();
        factory(Genre::class)->create();
        factory(Book::class)->create();
        $response = $this->get('formats');
        $response->assertSee('<td>1</td>', false);
    }
}
