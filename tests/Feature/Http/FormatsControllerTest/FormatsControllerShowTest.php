<?php

namespace Tests\Feature\Http\FormatsControllerTest;

use App\Models\Author;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use Tests\TestCase;

class FormatsControllerShowTest extends TestCase
{
    /**
     * @test
     */
    public function a_guest_can_visit_a_format_and_see_all_the_books_belonging_to_it()
    {
        $format1 = Format::factory()->create();
        $format2 = Format::factory()->create();
        Author::factory()->create();
        Genre::factory()->create();
        $book1Format1 = Book::factory()->create(['title' => 'The Book Of Dreams', 'format_id' => $format1->id]);
        $book2Format1 = Book::factory()->create(['title' => 'The Book Of Fate', 'format_id' => $format1->id]);
        $book3Format2 = Book::factory()->create(['title' => 'The Book Of Lore', 'format_id' => $format2->id]);

        $response = $this->get('/formats/' . $format1->id);

        $response->assertSee(e($format1->format));
        $response->assertSee($book1Format1->title);
        $response->assertSee($book2Format1->title);
        $response->assertDontSee($book3Format2->title);
    }
}
