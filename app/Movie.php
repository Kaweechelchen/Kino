<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $guarded = [];

    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }

    public function titles()
    {
        return $this->hasMany(Movietitle::class);
    }

    public function directors()
    {
        return $this->hasMany(Moviedirector::class);
    }

    public function actors()
    {
        return $this->hasMany(Movieactor::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
