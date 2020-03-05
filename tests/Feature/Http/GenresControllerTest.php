<?php

namespace Tests\Feature\Http;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Genre;
use App\Author;
use App\Format;
use App\Book;

class GenresControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_can_list_all_genres()
    {
        $genre1 = factory(Genre::class)->create(['type' => 'book']);
        $genre2 = factory(Genre::class)->create(['type' => 'record']);

        $response = $this->get('/genres');
        $response->assertSee(e($genre1->genre));
        $response->assertSee(e($genre2->genre));
        $response->assertSee('book');
        $response->assertSee('record');
    }

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

    /**
     * @test
     */
    public function a_user_can_edit_an_genre()
    {
        $this->signIn();

        $this->withoutExceptionHandling();
        $genre = factory(Genre::class)->create([
          'genre' => 'Fantasy',
      ]);

        $response = $this->get('/genres/' . $genre->id . '/edit');

        $response->assertSee('name="genre"', false);
        $response->assertSee('name="id"', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee('name="_method"', false);
        $response->assertSee('value="PUT"', false);
        $response->assertSee('value="Fantasy"', false);
        $response->assertSee('value="' . $genre->id . '"', false);
        $response->assertSee('input type="submit" value="Update"', false);
    }

    /**
     * @test
     * */
    public function a_user_can_create_a_genre()
    {
        $this->signIn();

        $response = $this->get('/genres/create');

        $response->assertSee('name="genre"', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee('input type="submit" value="Save"', false);
    }

    /**
    * @test
    */
    public function after_creating_an_genre_the_user_is_redirected_to_the_genres_index_view_and_success_message_is_shown()
    {
        $this->signIn();
        $genre = factory(Genre::class)->make();

        $response = $this->post('/genres', $genre->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/genres');

        $response = $this->get('/genres');
        $response->assertSee(e($genre->genre) . ' successfully added.');
    }

    /**
    * @test
    */
    public function after_updating_an_genre_the_user_is_redirected_to_the_genres_index_view_and_success_message_is_shown()
    {
        $this->signIn();
        $genre = factory(Genre::class)->create();
        $genre->genre = 'Kalle';

        $response = $this->patch('/genres/' . $genre->id, $genre->toArray());

        $response->assertStatus(302);
        $response->assertLocation('/genres');

        $response = $this->get('/genres');
        $response->assertSee(e($genre->genre) . ' successfully updated.');
    }

    /**
    * @test
    */
    public function after_deleting_an_genre_the_user_is_redirected_to_the_genres_index_view_and_success_message_is_shown()
    {
        $this->signIn();

        $genre = factory(Genre::class)->create();

        $response = $this->delete('/genres/' . $genre->id);

        $response->assertStatus(302);
        $response->assertLocation('/genres');

        $response = $this->get('/genres');
        $response->assertSee(e($genre->genre) . ' successfully deleted.');
    }

    /**
    * @test
    */
    public function if_no_genres_exists_then_show_no_genres_found_is_shown_in_genres_index_view()
    {
        $response = $this->get('/genres');
        $response->assertStatus(200);
        $response->assertSee('No genres found');
    }

    /**
    * @test
    */
    public function a_user_sees_the_add_genres_button_while_a_guest_dont_see_it()
    {
        $guestResponse = $this->get('/genres');
        $this->signIn();
        $userResponse = $this->get('/genres');

        $guestResponse->assertDontSee('Add genre');
        $userResponse->assertSee('Add genre');
    }

    /**
    * @test
    */
    public function when_visiting_the_index_page_the_amount_of_books_in_the_genre_is_shown()
    {
        factory(Author::class)->create();
        factory(Format::class)->create();
        factory(Genre::class)->create(['type' => 'book']);
        factory(Book::class)->create();
        $response = $this->get('genres');
        $response->assertSee('<td>1</td>', false);
    }
}
