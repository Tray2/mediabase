<?php

namespace Tests\Unit;
use App\Author;
use App\Book;
use App\Format;
use App\Genre;
use App\AuthorBook;
use App\BookView;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookViewTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_returns_an_array_of_authors()
    {
        $this->withExceptionHandling();

        $author = factory(Author::class)->create();
        factory(Format::class)->create(['type' => 'books']);
        factory(Genre::class)->create(['type' => 'books']);
        factory(Book::class)->create();
        factory(AuthorBook::class)->create([
            'author_id' => 1,
            'book_id' => 1
        ]);

        $book = BookView::first();

        $authors = $book->authors();
        $this->assertEquals($authors[0]->name, $author->name);
    }
}
