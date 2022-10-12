<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookShowView extends Model
{
    public function isStandalone(): bool
    {
        return $this->series === 'Standalone';
    }
}
