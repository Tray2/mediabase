<?php

namespace Tests\Feature\Http\RecordCollectionControllerTest;

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use Tests\TestCase;

class RecordCollectionControllerTestHelper extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Artist::factory()->create();
        Genre::factory()->create();
        Format::factory()->create();
    }
}
