<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RequiredIfNotStandalone implements Rule
{
    protected string $series;

    public function __construct($series)
    {
        if ($series === null) {
            $series = '';
        }
        $this->series = $series;
    }

    public function passes($attribute, $value): bool
    {
        if ($this->series === '') {
            return false;
        }

        if ($value === null) {
            return $this->series === 'Standalone';
        }

        return true;
    }

    public function message(): string
    {
        return 'The part is required when book belongs to a series.';
    }
}
