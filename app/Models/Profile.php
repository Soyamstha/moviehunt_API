<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'age',
        'is_kid'
    ];
    public function watchHistories()
    {
        return $this->hasMany(WatchHistory::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
