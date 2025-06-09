<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    Public function user()
    {
        return $this->belongsTo(User::class);
    }
    Public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
