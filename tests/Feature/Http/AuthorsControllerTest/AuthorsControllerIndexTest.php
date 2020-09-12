<?php

namespace Tests\Feature\Http\AuthorsControllerTest;

use App\Models\Author;
use App\Models\AuthorBook;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use Tests\TestCase;

class AuthorsControllerIndexTest extends TestCase
{
    /**
     * @test
     */
    public function guests_can_browse_the_authors()
    {
        $author1 = Author::factory()->create();
        $author2 = Author::factory()->create();

        $response = $this->get('/authors');
        $response->assertSee($author1->name);
        $response->assertSee($author2->name);
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
    public function when_visiting_the_index_page_the_amount_of_books_by_the_author_is_shown()
    {
        Author::factory()->create();
        Genre::factory()->create();
        Format::factory()->create();
        Book::factory()->create();
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
