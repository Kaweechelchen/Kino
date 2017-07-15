<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovieScreening extends Model
{
    protected $guarded = [];
    protected $table   = 'movie_screening';
    protected $hidden  = ['created_at', 'updated_at'];
}
