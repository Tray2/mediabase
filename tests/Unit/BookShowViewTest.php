<?php

use App\Models\BookShowView;

test('returns true if the book is a standalone', function () {
    $book = new BookShowView();
    $book->series = 'Standalone';
    $this->assertTrue($book->isStandalone());
});

test('returns false if the book is not a standalone', function () {
    $book = new BookShowView();
    $book->series = 'Some series';
    $this->assertFalse($book->isStandalone());
});
