<?php

namespace Tests\Feature\Http\GenresControllerTest;

use App\Author;
use App\Book;
use App\Format;
use App\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenresControllerShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  @test
     */
    public function a_guest_can_visit_a_genre_and_see_all_the_books_belonging_to_it()
    {
        $genre1 = factory(Genre::class)->create();
        $genre2 = factory(Genre::class)->create();
        factory(Author::class)->create();
        factory(Format::class)->create();
        $book1Genre1 = factory(Book::class)->create(['title' => 'The Eye Of The World', 'genre_id' => $genre1->id]);
        $book2Genre1 = factory(Book::class)->create(['title' => 'The Great Hunt', 'genre_id' => $genre1->id]);
        $book3Genre2 = factory(Book::class)->create(['title' => 'Laravel Up & Running', 'genre_id' => $genre2->id]);

        $response = $this->get('/genres/' . $genre1->id);

        $response->assertSee(htmlentities($genre1->genre, ENT_QUOTES));
        $response->assertSee($book1Genre1->title);
        $response->assertSee($book2Genre1->title);
        $response->assertDontSee($book3Genre2->title);
    }
}
