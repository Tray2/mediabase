<?php

use App\Models\Record;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\delete;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;

uses(FastRefreshDatabase::class);

it('deletes a record', function () {
    $record = Record::factory()->create();
    assertDatabaseCount(Record::class, 1);

    delete(route('records.delete', $record))
        ->assertRedirect(route('records.index'));

    assertDatabaseCount(Record::class, 0);
});
