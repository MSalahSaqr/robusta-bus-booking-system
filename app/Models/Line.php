<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    use HasFactory;

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
    public function stations()
    {
        return $this->belongsToMany(Station::class, 'lines_stations')->withPivot('order', 'created_at', 'updated_at');
    }
}
