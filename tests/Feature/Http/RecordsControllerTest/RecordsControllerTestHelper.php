<?php

namespace Tests\Feature\Http\RecordsControllerTest;

use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;

class RecordsControllerTestHelper extends TestCase
{
    protected Collection $genre;
    protected Collection $format;
    protected Collection $artist;

    protected function createForeignKeys($quantity = 1)
    {
        $this->genre = Genre::factory($quantity)->create(['media_type_id' => env('RECORDS')]);
        $this->format = Format::factory($quantity)->create(['media_type_id' => env('RECORDS')]);
        $this->artist = Artist::factory($quantity)->create();
    }
}
