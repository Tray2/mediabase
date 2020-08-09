<?php

namespace Tests\Feature\Db;

use App\MediaType;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaTypesTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function the_media_must_be_unique()
    {
        $this->expectException(QueryException::class);
        MediaType::create(['media' => 'Books']);
        $this->assertDatabaseCount('media_types',5);
    }
}
