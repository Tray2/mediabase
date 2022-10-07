<?php

use App\Models\Record;
use Database\Seeders\MediaTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\delete;

uses(RefreshDatabase::class);

it('deletes a record', function() {
    $this->seed(MediaTypeSeeder::class);
    $record = Record::factory()->create();
    assertDatabaseCount(Record::class, 1);

    delete(route('records.delete', $record))
        ->assertRedirect(route('records.index'));

    assertDatabaseCount(Record::class, 0);
});
