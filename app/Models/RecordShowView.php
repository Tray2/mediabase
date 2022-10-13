<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordShowView extends Model
{
    public function isVarious(): bool
    {
        return $this->artist === 'Various Artists';
    }
}
