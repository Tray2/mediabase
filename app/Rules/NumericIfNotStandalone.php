<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NumericIfNotStandalone implements Rule
{
    protected string $series;

    protected bool $isArray = false;

    public function __construct($series)
    {
        if (is_array($series)) {
            $this->isArray = true;
        } elseif ($series === null) {
            $this->series = '';
        } else {
            $this->series = $series;
        }
    }

    public function passes($attribute, $value): bool
    {
        if ($this->isArray) {
            return false;
        }
        if ($this->series === 'Standalone') {
            return true;
        }

        return is_numeric($value);
    }

    public function message(): string
    {
        return 'The part must be a number.';
    }
}
