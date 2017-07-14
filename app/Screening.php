<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    protected $guarded = [];

    public function scopeUpcoming($query)
    {
        return $query->where('screening', '>', date('Y-m-d H:i:s'))->orderBy('screening');
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
