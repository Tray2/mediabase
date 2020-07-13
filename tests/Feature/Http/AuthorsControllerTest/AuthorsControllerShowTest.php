<?php

namespace Tests\Feature\Http\AuthorsControllerTest;

use App\Author;
use App\AuthorBook;
use App\Book;
use App\Format;
use App\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorsControllerShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function anyone_can_visit_an_author_and_see_all_the_books_belonging_to_the_author()
    {
        factory(Format::class)->create();
        factory(Genre::class)->create();
        $author1 = factory(Author::class)->create();
        $author2 = factory(Author::class)->create(['first_name' => 'Secret', 'last_name' => 'Arne']);
        $book1Author1 = factory(Book::class)->create();
        $book2Author1 = factory(Book::class)->create();
        $book3Author2 = factory(Book::class)->create(['title' => 'Invisible Book']);

        factory(AuthorBook::class)->create([
            'book_id' => $book1Author1->id,
            'author_id' => $author1->id
        ]);

        factory(AuthorBook::class)->create([
            'book_id' => $book2Author1->id,
            'author_id' => $author1->id
        ]);

        factory(AuthorBook::class)->create([
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
        $author = factory(Author::class)->create();
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
        $author = factory(Author::class)->create();
        $response = $this->get('/authors/' . $author->id);
        $response->assertSee('Author has no books');
    }

    /**
     * @test
     */
    public function when_a_user_visits_the_authors_show_view_the_add_book_link_contains_the_author_id_as_a_get_parameter()
    {
        $this->signIn();
        $author = factory(Author::class)->create();
        $response = $this->get('/authors/' . $author->id);
        $response->assertSee('?author_id=' . $author->id);
    }

    /**
     * @test
     */
    public function slug_can_be_used_in_place_of_id_the_get_the_author_for_the_show_view()
    {
        $author = factory(Author::class)->create();
        $response = $this->get('/authors/' . $author->slug);
        $response->assertSee($author->name);
    }
}
