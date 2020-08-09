<?php

namespace Tests\Feature\Http\BookCollectionControllerTest;

use App\Author;
use App\Format;
use App\Genre;
use Tests\TestCase;

class BookCollectionControllerTestHelper extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        factory(Author::class)->create();
        factory(Genre::class)->create();
        factory(Format::class)->create();
    }
}
