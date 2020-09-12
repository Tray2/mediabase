<?php

namespace Tests\Feature\Http\BooksControllerTest;

use Tests\TestCase;
use App\Models\Author;
use App\Models\Format;
use App\Models\Genre;

class BooksControllerTestHelper extends TestCase
{
    protected $genre = '';
    protected $format = '';
    protected $author = '';

    protected function createForeignKeys($quantity = 1)
    {
        $this->genre = Genre::factory($quantity)->create();
        $this->format = Format::factory($quantity)->create();
        $this->author = Author::factory($quantity)->create();
    }














}
