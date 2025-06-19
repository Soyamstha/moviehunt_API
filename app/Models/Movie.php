<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
class Movie extends Model implements HasMedia
{
    use InteractsWithMedia;
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
      public function registerMediaCollections(): void
    {
        $this->addMediaCollection('preview')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumbnail')
                    ->width(100)
                    ->height(100)
                    ->nonQueued(); #included this since we are not queueing conversions
            });
    }
}
