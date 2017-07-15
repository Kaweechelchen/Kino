<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Screening extends Model
{
    protected $guarded = [];
    protected $hidden  = ['created_at', 'updated_at'];

    public function movies()
    {
        return $this->belongsToMany(Movie::class)
            ->withPivot(
                'theatre_id',
                'hall',
                'format_id',
                'language_id'
            )
            ->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('screening', function (Builder $builder) {
            $builder->where('screening', '>', date('Y-m-d H:i:s'));
        });
    }
}
