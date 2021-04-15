<?php

namespace App\Repositories\Interfaces;

interface TripRepositoryInterface
{

    public function getTripWithStaions($id);

    public function getTripReservations($id);

}
