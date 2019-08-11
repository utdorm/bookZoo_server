<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;
    
    public $fillable = ['title', 'author', 'summary'];

    public function bookTags()
    {
        return $this->hasMany(\App\Model\BookTag::class);
    }
    public function rentRequest()
    {
        return $this->hasMany('App\Model\RentRequest');
    }
}
