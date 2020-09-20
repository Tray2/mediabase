<?php

namespace Tests\Feature\Http\RecordsControllerTest;

use Tests\TestCase;
use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;

class RecordsControllerTestHelper extends TestCase
{
    protected $genre = '';
    protected $format = '';
    protected $artist = '';

    protected function createForeignKeys($quantity = 1)
    {
        $this->genre = Genre::factory($quantity)->create();
        $this->format = Format::factory($quantity)->create();
        $this->artist = Artist::factory($quantity)->create();
    }
}
