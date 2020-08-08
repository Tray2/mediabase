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
    public function the_media_types_table_has_an_id()
    {
        $mediaType = MediaType::create(['media' => 'Book']);
        $this->assertEquals(1, $mediaType->id);
    }

    /**
    * @test
    */
    public function the_media_types_table_has_a_media_type()
    {
        $mediaType = MediaType::create(['media' => 'Book']);
        $this->assertEquals('Book', $mediaType->media);
    }

    /**
    * @test
    */
    public function the_media_must_be_unique()
    {
        $this->expectException(QueryException::class);
        MediaType::create(['media' => 'Book']);
        MediaType::create(['media' => 'Book']);
        $this->assertDatabaseCount('media_types',1);
    }
}
