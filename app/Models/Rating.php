<?php

namespace App\Models;
use App\Models\Profile;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    public function profile(){
        return $this->belongsTo(Profile::class);
    }
}
