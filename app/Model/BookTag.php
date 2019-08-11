<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BookTag extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'tag_id',
    ];
    public function book()
    {
        return $this->belongsTo(\App\Model\Book::class);
    }

    public function tag()
    {
        return $this->belongsTo(\App\Model\Tag::class);
    }
}
