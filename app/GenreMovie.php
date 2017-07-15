<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GenreMovie extends Model
{
    protected $guarded = [];
    protected $table   = 'genre_movie';
    protected $hidden  = ['created_at', 'updated_at'];
}
