<?php

use App\Models\Record;
use App\Models\User;
use function Pest\Laravel\assertDatabaseCount;

it('deletes a record', function () {
    $this->user = User::factory()->create();
    $record = Record::factory()->create();
    assertDatabaseCount(Record::class, 1);

    actingAs($this->user)->delete(route('records.delete', $record))
        ->assertRedirect(route('records.index'));

    assertDatabaseCount(Record::class, 0);
});
