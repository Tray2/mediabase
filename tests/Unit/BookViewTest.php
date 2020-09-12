<?php

namespace Tests\Unit;
use App\Models\Author;
use App\Models\AuthorBook;
use App\Models\Book;
use App\Models\BookView;
use App\Models\Format;
use App\Models\Genre;
use Tests\TestCase;

class BookViewTest extends TestCase
{
    /**
    * @test
    */
    public function it_returns_an_array_of_authors()
    {
        $author = Author::factory()->create();
        Format::factory()->create(['media_type_id' => env('BOOKS')]);
        Genre::factory()->create(['media_type_id' => env('BOOKS')]);
        Book::factory()->create();
        AuthorBook::factory()->create([
            'author_id' => 1,
            'book_id' => 1
        ]);

        $book = BookView::first();

        $authors = $book->authors();
        $this->assertEquals($authors[0]->name, $author->name);
    }
}
