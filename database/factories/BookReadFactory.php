<?php


use App\BookRead;

$factory->define(BookRead::class, function () {
    return [
        'book_id' => 1,
        'user_id' => 1
    ];
});
