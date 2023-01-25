<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Nicebooks\Isbn\IsbnTools;

class Isbn implements InvokableRule
{
    public function __invoke($attribute, $value, $fail): void
    {
        $tools = new IsbnTools();
        if (is_array($value)) {
            $fail('The :attribute must be a string.');
        } else if (! $tools->isValidIsbn($value)) {
            $fail('The :attribute must be a valid ISBN10 or ISBN13.');
        }
    }
}
