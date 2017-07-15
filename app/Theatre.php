<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Theatre extends Model
{
    protected $guarded   = [];
    public $incrementing = false;
    protected $hidden  = ['created_at', 'updated_at'];
}
