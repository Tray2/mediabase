<?php

use App\BookCollection;

$factory->define(BookCollection::class, function () {
    return [
        'book_id' => 1,
        'user_id' => 1
    ];
});
