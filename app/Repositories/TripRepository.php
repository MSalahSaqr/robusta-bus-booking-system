<?php namespace App\Repositories;

use App\Models\Trip;
use App\Repositories\Interfaces\TripRepositoryInterface;

class TripRepository implements TripRepositoryInterface
{

    public function getTripWithStaions($id)
    {
        $trips = Trip::with('line.stations')->find($id);
        if ($trips != null) {
            $trips->toArray();
        } else {
            $trips = [];
        }

        return $trips;
    }

    public function getTripReservations($id)
    {
        $trips = Trip::with('bus.seats.reservations')->find($id);
        if ($trips != null) {
            $trips->toArray();
        } else {
            $trips = [];
        }

        return $trips;
    }
}
