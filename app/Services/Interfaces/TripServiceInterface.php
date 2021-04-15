<?php

namespace App\Services\Interfaces;

interface TripServiceInterface
{
    public function getAllAvailableSeats($fromStationId, $toStationId, $tripId);
    public function reserveSeat($seatId, $fromStationId, $toStationId, $tripId);
}
