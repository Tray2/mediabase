<?php

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Record;
use Database\Seeders\MediaTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('shows a list of the artists records excluding the one showing', function() {
    $this->seed(MediaTypeSeeder::class);
    $recordId = MediaType::query()
        ->where('name', 'Record')
        ->value('id');
    Record::factory()
       ->for(Artist::factory())
       ->for(Genre::factory([
           'media_type_id' => $recordId,
       ]))
       ->for(Format::factory([
           'media_type_id' => $recordId,
       ]))
    ->count(3)
    ->create();

    $record = Record::inRandomOrder()->first();

    get(route('records.show', $record))
        ->assertOk()
        ->assertSeeInOrder(Record::query()
            ->whereNot('id', $record->id)
            ->orderBy('release_year')
            ->pluck('title')
            ->toArray()
        );
});