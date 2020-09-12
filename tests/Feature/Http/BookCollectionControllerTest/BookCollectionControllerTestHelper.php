<?php

namespace Tests\Feature\Http\BookCollectionControllerTest;

use App\Models\Author;
use App\Models\Format;
use App\Models\Genre;
use Tests\TestCase;

class BookCollectionControllerTestHelper extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Author::factory()->create();
        Genre::factory()->create();
        Format::factory()->create();
    }
}
