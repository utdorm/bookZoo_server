<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ConfirmCode extends Model
{
    public $fillable = ['phoneNumber', 'code'];
    
}
