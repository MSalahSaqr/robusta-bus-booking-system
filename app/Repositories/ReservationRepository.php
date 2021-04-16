<?php namespace App\Repositories;

use App\Models\Reservation;
use App\Repositories\Interfaces\ReservationRepositoryInterface;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function createReservation($seatId, $fromStationId, $toStationId, $tripId, $userId)
    {
        $Reservation = new Reservation;
        $Reservation->seat_id = $seatId;
        $Reservation->from_station = $fromStationId;
        $Reservation->to_station = $toStationId;
        $Reservation->trip_id = $toStationId;
        $Reservation->user_id = $userId;
        $Reservation->save();
        return $Reservation;
    }

}
