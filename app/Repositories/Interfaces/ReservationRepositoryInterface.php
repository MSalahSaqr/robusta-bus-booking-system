<?php

namespace App\Repositories\Interfaces;

interface ReservationRepositoryInterface
{

    public function createReservation($seatId, $fromStationId, $toStationId, $tripId);

}
