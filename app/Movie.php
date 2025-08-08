<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $table = 'movies';

   // public $incrementing = false; // porque el id es UUID
    //protected $keyType = 'string';

    protected $fillable = [
        'id',
        'title',
        'original_title',
        'original_title_romanised',
        'image',
        'movie_banner',
        'description',
        'director',
        'producer',
        'release_date',
        'running_time',
        'rt_score',
    ];
}
