<?php

namespace Tests\Feature\Db;

use App\MediaType;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class MediaTypesTest extends TestCase
{
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
