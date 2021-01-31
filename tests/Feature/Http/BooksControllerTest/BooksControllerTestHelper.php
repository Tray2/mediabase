<?php

namespace Tests\Feature\Http\BooksControllerTest;

use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use App\Models\Author;
use App\Models\Format;
use App\Models\Genre;

class BooksControllerTestHelper extends TestCase
{
    protected Collection $genre;
    protected Collection $format;
    protected Collection $author;

    protected function createForeignKeys($quantity = 1)
    {
        $this->genre = Genre::factory($quantity)->create(['media_type_id' => env('BOOKS')]);
        $this->format = Format::factory($quantity)->create(['media_type_id' => env('BOOKS')]);
        $this->author = Author::factory($quantity)->create();
    }
}
