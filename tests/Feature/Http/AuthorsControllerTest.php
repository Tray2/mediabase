<?php

namespace Tests\Feature\Http;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Author;
use App\AuthorBook;
use App\Book;
use App\BookCollection;
use App\Genre;
use App\Format;

class AuthorsControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function guests_can_browse_the_authors()
    {
        $this->withoutExceptionHandling();
        $author1 = factory(Author::class)->create();
        $author2 = factory(Author::class)->create();

        $response = $this->get('/authors');
        $response->assertSee($author1->name);
        $response->assertSee($author2->name);
    }

    /**
    * @test
    */
    public function anyone_can_visit_an_author_and_see_all_the_books_belonging_to_the_author()
    {
        $this->withoutExceptionHandling();
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
    public function an_user_can_edit_an_author()
    {
        $this->signIn();

        $author = factory(Author::class)->create([
          'last_name' => 'Jordan',
          'first_name' => 'Robert'
        ]);

        $response = $this->get('/authors/' . $author->id . '/edit');

        $response->assertSee('name="first_name"', false);
        $response->assertSee('name="last_name"', false);
        $response->assertSee('name="id"', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee('name="_method"', false);
        $response->assertSee('value="Robert"', false);
        $response->assertSee('value="Jordan"', false);
        $response->assertSee('value="' . $author->id . '"', false);
    }

    /**
    * @test
    */
    public function user_can_create_authors()
    {
        $this->signIn();

        $response = $this->get('/authors/create');

        $response->assertSee('name="first_name"', false);
        $response->assertSee('name="last_name"', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee('input type="submit" value="Save"', false);
    }

    /**
    * @test
    */
    public function after_creating_an_author_the_user_is_redirected_to_the_authors_index_view_and_success_message_is_shown()
    {
        $this->signIn();
        $author = factory(Author::class)->make();

        $response = $this->post('/authors', $author->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/authors');

        $response = $this->get('/authors');
        $response->assertSee($author->name . ' successfully added.');
    }

    /**
    * @test
    */
    public function after_updating_an_author_the_user_is_redirected_to_the_authors_index_view_and_success_message_is_shown()
    {
        $this->signIn();
        $author = factory(Author::class)->create();
        $author->first_name = 'Kalle';

        $response = $this->patch('/authors/' . $author->id, $author->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/authors');

        $response = $this->get('/authors');
        $response->assertSee($author->name . ' successfully updated.');
    }

    /**
    * @test
    */
    public function after_deleting_an_author_the_user_is_redirected_to_the_authors_index_view_and_success_message_is_shown()
    {
        $this->signIn();

        $author = factory(Author::class)->create();

        $response = $this->delete('/authors/' . $author->id);

        $response->assertStatus(302);
        $response->assertLocation('/authors');

        $response = $this->get('/authors');
        $response->assertSee($author->name . ' successfully deleted.');
    }

    /**
    * @test
    */
    public function if_no_authors_exists_then_show_no_authors_found_is_shown_in_authors_index_view()
    {
        $response = $this->get('/authors');
        $response->assertStatus(200);
        $response->assertSee('No authors found');
    }

    /**
    * @test
    */
    public function users_see_the_add_authors_button_while_guests_dont_see_it_when_visiting_the_authors_index_view()
    {
        $guestResponse = $this->get('/authors');
        $this->signIn();
        $userResponse = $this->get('/authors');

        $guestResponse->assertDontSee('Add author');
        $userResponse->assertSee('Add author');
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

    /**
    * @test
    */
    public function when_visiting_the_index_page_the_amount_of_books_by_the_author_is_shown()
    {
        factory(Author::class)->create();
        factory(Format::class)->create();
        factory(Genre::class)->create();
        factory(Book::class)->create();
        $response = $this->get('authors');
        $response->assertSee('<td>0</td>', false);
        AuthorBook::create([
            'author_id' => 1,
            'book_id' => 1
        ]);
        $response = $this->get('authors');
        $response->assertSee('<td>1</td>', false);
    }
}
