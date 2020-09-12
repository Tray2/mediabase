<?php

namespace Tests\Feature\Http\GenresControllerTest;

use App\Models\Author;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use Tests\TestCase;

class GenresControllerShowTest extends TestCase
{
    /**
     *  @test
     */
    public function a_guest_can_visit_a_genre_and_see_all_the_books_belonging_to_it()
    {
        $genre1 = Genre::factory()->create();
        $genre2 = Genre::factory()->create();
        Author::factory()->create();
        Format::factory()->create();
        $book1Genre1 = Book::factory()->create(['title' => 'The Eye Of The World', 'genre_id' => $genre1->id]);
        $book2Genre1 = Book::factory()->create(['title' => 'The Great Hunt', 'genre_id' => $genre1->id]);
        $book3Genre2 = Book::factory()->create(['title' => 'Laravel Up & Running', 'genre_id' => $genre2->id]);

        $response = $this->get('/genres/' . $genre1->id);

        $response->assertSee(htmlentities($genre1->genre, ENT_QUOTES));
        $response->assertSee($book1Genre1->title);
        $response->assertSee($book2Genre1->title);
        $response->assertDontSee($book3Genre2->title);
    }
}
