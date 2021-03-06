<?php

namespace Tests\Feature\Http\AuthorsControllerTest;

use App\Models\Author;
use App\Models\AuthorBook;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use Tests\TestCase;

class AuthorsControllerShowTest extends TestCase
{
    /**
     * @test
     */
    public function anyone_can_visit_an_author_and_see_all_the_books_belonging_to_the_author()
    {
        Format::factory()->create();
        Genre::factory()->create();
        $author1 = Author::factory()->create();
        $author2 = Author::factory()->create(['first_name' => 'Secret', 'last_name' => 'Arne']);
        $book1Author1 = Book::factory()->create();
        $book2Author1 = Book::factory()->create();
        $book3Author2 = Book::factory()->create(['title' => 'Invisible Book']);

        AuthorBook::factory()->create([
            'book_id' => $book1Author1->id,
            'author_id' => $author1->id
        ]);

        AuthorBook::factory()->create([
            'book_id' => $book2Author1->id,
            'author_id' => $author1->id
        ]);

        AuthorBook::factory()->create([
            'book_id' => $book3Author2->id,
            'author_id' => $author2->id
        ]);

        $response = $this->get('/authors/' . $author1->id);

        $response->assertSee($author1->name);
        $response->assertSee($book1Author1->title);
        $response->assertSee($book2Author1->title);
        $response->assertDontSee($book3Author2->title);
    }

    /**
     * @test
     */
    public function users_sees_the_add_book_while_guests_dont_see_it_when_visiting_the_author_show_view()
    {
        $author = Author::factory()->create();
        $responseGuest = $this->get('/authors/' . $author->id);
        $this->signIn();
        $responseUser = $this->get('/authors/' . $author->id);
        $responseGuest->assertDontSee('Add Book');
        $responseUser->assertSee('Add book');
    }

    /**
     * @test
     */
    public function when_visiting_an_author_that_has_no_books_the_message_authors_has_no_books_is_shown()
    {
        $author = Author::factory()->create();
        $response = $this->get('/authors/' . $author->id);
        $response->assertSee('Author has no books');
    }

    /**
     * @test
     */
    public function when_a_user_visits_the_authors_show_view_the_add_book_link_contains_the_author_id_as_a_get_parameter()
    {
        $this->signIn();
        $author = Author::factory()->create();
        $response = $this->get('/authors/' . $author->id);
        $response->assertSee('?author_id=' . $author->id);
    }

    /**
     * @test
     */
    public function slug_can_be_used_in_place_of_id_the_get_the_author_for_the_show_view()
    {
        $author = Author::factory()->create();
        $response = $this->get('/authors/' . $author->slug);
        $response->assertSee($author->name);
    }
}
