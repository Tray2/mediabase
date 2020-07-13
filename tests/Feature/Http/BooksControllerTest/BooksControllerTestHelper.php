<?php

namespace Tests\Feature\Http\BooksControllerTest;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Author;
use App\Format;
use App\Genre;

class BooksControllerTestHelper extends TestCase
{
    use RefreshDatabase;

    protected $genre = '';
    protected $format = '';
    protected $author = '';

    protected function createForeignKeys($quantity = 1)
    {
        $this->genre = factory(Genre::class, $quantity)->create();
        $this->format = factory(Format::class, $quantity)->create();
        $this->author = factory(Author::class, $quantity)->create();
    }














}
