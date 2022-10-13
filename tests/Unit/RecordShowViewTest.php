<?php

use App\Models\RecordShowView;

it('returns true if the artist is various artists', function () {
    $record = new RecordShowView();
    $record->artist = 'Various Artists';
    $this->assertTrue($record->isVarious());
});

it('returns false if the artist is not various artists', function () {
    $record = new RecordShowView();
    $record->artist = 'Some Artist';
    $this->assertFalse($record->isVarious());
});
