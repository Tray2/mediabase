<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookCollectionView extends Model
{
    public function authors()
    {
        return Author::whereIn('id', explode(',', $this->author_id))->get();
    }
}
