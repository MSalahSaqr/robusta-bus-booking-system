<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    public function fromStation()
    {
        return $this->belongsTo(Station::class, 'from');
    }

    public function toStation()
    {
        return $this->belongsTo(Station::class, 'to_station');
    }
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
