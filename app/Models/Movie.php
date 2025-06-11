<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    public $fillable = [
'title',
'description',
'release_date',
'duration',
'rating',
'language',
'thumbnail_url',
'trailer_url',
'video_url',
'genres',
    ];
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'moviegenres');
    }
}
