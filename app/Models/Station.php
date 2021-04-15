<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    public function lines()
    {
        return $this->belongsToMany(Line::class, 'lines_stations')->withPivot('order', 'created_at', 'updated_at');
    }
}
