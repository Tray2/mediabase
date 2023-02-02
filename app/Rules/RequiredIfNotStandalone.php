<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RequiredIfNotStandalone implements Rule
{
    protected string $series;

    protected bool $isArray = false;

    public function __construct($series)
    {
        if (is_array($series)) {
            $this->isArray = true;
        } elseif ($series === null) {
            $series = '';
        } else {
            $this->series = $series;
        }
    }

    public function passes($attribute, $value): bool
    {
        if ($this->isArray) {
            return false;
        }
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
