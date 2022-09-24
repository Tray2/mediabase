<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MinWords implements Rule
{
    protected int $minWordCount;

    public function __construct($minWordCount)
    {
        $this->minWordCount = $minWordCount;
    }

    public function passes($attribute, $value): bool
    {
        return str_word_count($value) >= $this->minWordCount;
    }

    public function message(): string
    {
        return "The blurb must be at least $this->minWordCount words.";
    }
}
