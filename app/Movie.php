<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $guarded = [];

    public function languages()
    {
        return $this->hasMany(Language::class);
    }
}
