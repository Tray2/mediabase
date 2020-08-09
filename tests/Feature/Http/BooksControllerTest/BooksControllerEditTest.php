<?php

namespace Tests\Feature\Http\BooksControllerTest;

use App\AuthorBook;
use App\Book;

class BooksControllerEditTest extends BooksControllerTestHelper
{
    /**
     *  @test
     */
    public function users_can_edit_a_book()
    {
        $this->signIn();

        $this->createForeignKeys(5);
        $book = factory(Book::class)->create();
        AuthorBook::create([
            'author_id' => 1,
            'book_id' => 1
        ]);

        $response = $this->get('/books/' . $book->id . '/edit');

        $response->assertSee('name="title"', false);
        $response->assertSee('name="series"', false);
        $response->assertSee('name="part"', false);
        $response->assertSee('name="isbn"', false);
        $response->assertSee('name="released"', false);
        $response->assertSee('name="reprinted"', false);
        $response->assertSee('name="pages"', false);
        $response->assertSee('name="blurb"', false);
        $response->assertSee('name="id"', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee('name="_method"', false);

        $response->assertSee('value="PUT"', false);
        $response->assertSee('value="' .$book->title . '"', false);
        $response->assertSee('value="' . $book->series . '"', false);
        $response->assertSee('value="' . $book->part . '"', false);
        $response->assertSee('value="' . $book->isbn . '"', false);
        $response->assertSee('value="' . $book->released . '"', false);
        $response->assertSee('value="' . $book->reprinted . '"', false);
        $response->assertSee('value="' . $book->pages . '"', false);
        $response->assertSee($book->blurb, false);
        $response->assertSee('input type="submit" value="Update"', false);

        $response->assertSee('name="genre_id"', false);
        $response->assertSee('name="format_id"', false);
        $response->assertSee('<option value="1" selected>' . $book->genre->genre . '</option>', false);
    }

}
