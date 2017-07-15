<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryMovie extends Model
{
    protected $guarded = [];
    protected $table   = 'category_movie';
    protected $hidden  = ['created_at', 'updated_at'];
}
