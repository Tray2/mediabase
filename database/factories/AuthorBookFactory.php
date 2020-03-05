<?php

use App\AuthorBook;

$factory->define(AuthorBook::class, function () {
    return [
        'author_id' => 1,
        'book_id' => 1
    ];
});
