<?php

namespace Tests\Feature\Http\FormatsControllerTest;

use App\Author;
use App\Book;
use App\Format;
use App\Genre;
use Tests\TestCase;

class FormatsControllerShowTest extends TestCase
{
    /**
     * @test
     */
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
}
