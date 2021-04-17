<?php

namespace App\Services;

use App\Exceptions\InvalidStationsOrderException;
use App\Exceptions\NotFoundException;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Interfaces\TripRepositoryInterface;
use App\Services\Interfaces\TripServiceInterface;

//This class is responsible for business logic related to Trips

class TripService implements TripServiceInterface
{

    public function __construct(TripRepositoryInterface $tripRepo, ReservationRepositoryInterface $reservationRepo)
    {
        $this->tripRepo = $tripRepo;
        $this->reservationRepo = $reservationRepo;
    }

    //this function returns the trip based on its id with all of its reservations
    private function getTripWithReservations($tripId)
    {

        try
        {
            $trip = $this->tripRepo->getTripReservations($tripId);

        } catch (Expetion $e) {
            throw $e;
        }
        if ($trip == []) {
            throw new NotFoundException('Trip not found by ID ' . $tripId);
        }
        return $trip;
    }

    //this function returns all seats of a trip based on its id and all of each seat reservations
    private function getSeatswithReservations($tripId)
    {
        $trip = $this->getTripWithReservations($tripId);
        $seats = $trip['bus']['seats']->toArray();
        if ($seats == []) {
            throw new NotFoundException('Trip By Id ' . $tripId . ' Bus does not contain any seats');
        }
        return $seats;
    }

    //this function extracts the reservations of a specific seat on a trip
    private function getSeatReservations($seatId, $seatsWithReservations)
    {
        $seatInArray = array_filter($seatsWithReservations, function ($item) use ($seatId) {
            return $item['id'] == $seatId;
        });
        $seatInArray = array_values($seatInArray);
        if ($seatInArray == []) {
            throw new NotFoundException('Seat not found by ID ' . $seatId);
        }
        return $seatInArray[0];
    }

    //this function returns an associative array of [stationId => order,... ]
    private function getTripStationsOrder($tripId)
    {
        $stationsOrder = [];
        $stations = $this->tripRepo->getTripWithStaions($tripId)['line']['stations'];
        foreach ($stations as $station) {
            $stationsOrder[$station['id']] = $station['pivot']['order'];
        }
        return $stationsOrder;
    }

    //this function checks wheather a seat is available or not
    private function CheckSeatAvailablityHelper($seatWithReservations, $fromStationId, $toStationId, $stationsOrders)
    {
        if (!array_key_exists($fromStationId, $stationsOrders) && !array_key_exists($toStationId, $stationsOrders)) {
            throw new NotFoundException('Stations not available on this trip route by IDs( ' . $fromStationId . ' , ' . $toStationId . ' )');
        }

        if (!array_key_exists($fromStationId, $stationsOrders)) {
            throw new NotFoundException('Station not found by ID ' . $fromStationId);
        }

        if (!array_key_exists($toStationId, $stationsOrders)) {
            throw new NotFoundException('Station not found by ID ' . $toStationId);
        }

        if ($stationsOrders[$toStationId] <= $stationsOrders[$fromStationId]) {
            throw new InvalidStationsOrderException();
        }

        $seatReservations = $seatWithReservations['reservations'];

        if ($seatReservations == []) {
            return true;
        }

        foreach ($seatReservations as $seatReservation) {
            if ($stationsOrders[$seatReservation['from_station']] >= $stationsOrders[$toStationId]) {
                continue;
            }
            if ($stationsOrders[$seatReservation['to_station']] <= $stationsOrders[$fromStationId]) {
                continue;
            }
            return false;
        }
        return true;
    }

    //this function prepares the needed date for CheckSeatAvailablityHelper function and calls it
    private function CheckSeatAvailability($seatId, $fromStationId, $toStationId, $tripId)
    {
        $seatsWithReservations = $this->getSeatswithReservations($tripId);
        $seatWithReservations = $this->getSeatReservations($seatId, $seatsWithReservations);
        $stationsOrders = $this->getTripStationsOrder($tripId);
        return $this->CheckSeatAvailablityHelper($seatWithReservations, $fromStationId, $toStationId, $stationsOrders);
    }

    //this function returns all seats of a specific trip and wither they are available or not
    public function getAllAvailableSeats($fromStationId, $toStationId, $tripId)
    {
        $seats = [];
        $seatsWithReservations = $this->getSeatswithReservations($tripId);
        $stationsOrders = $this->getTripStationsOrder($tripId);
        foreach ($seatsWithReservations as $seat) {
            $seatData['id'] = $seat['id'];
            $seatData['code'] = $seat['code'];
            $seatWithReservations = $this->getSeatReservations($seat['id'], $seatsWithReservations);
            $seatData['available'] = $this->CheckSeatAvailablityHelper($seatWithReservations, $fromStationId, $toStationId, $stationsOrders);
            $seats[] = $seatData;
        }
        return $seats;
    }

    //this function check weather a seat is available or not and if available reserves it and return the reservation details
    // and if not returns null
    public function reserveSeat($seatId, $fromStationId, $toStationId, $tripId, $userId)
    {
        if ($this->CheckSeatAvailability($seatId, $fromStationId, $toStationId, $tripId)) {
            $reservation = $this->reservationRepo->createReservation($seatId, $fromStationId, $toStationId, $tripId, $userId);
            return $reservation;
        } else {
            return null;
        }
    }
}
