<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;
    public function bookTags()
    {
        return $this->hasMany(\App\Model\BookTag::class);
    }
}
