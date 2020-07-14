<?php

namespace Tests\Feature\Http\BookCollectionControllerTest;

use App\Author;
use App\Format;
use App\Genre;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookCollectionControllerTestHelper extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        factory(Author::class)->create();
        factory(Genre::class)->create();
        factory(Format::class)->create();
    }



}
