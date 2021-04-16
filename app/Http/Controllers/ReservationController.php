<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\TripServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{

    public function __construct(TripServiceInterface $tripService)
    {
        $this->tripService = $tripService;
    }

    public function show(Request $request)
    {
        $rules = array(
            'tripId' => 'required|integer',
            'fromStation' => 'required|integer',
            'toStation' => 'required|integer',
        );
        $messages = array(
            'tripId.required' => 'Please enter a tripId.',
            'fromStation.required' => 'Please enter a fromStation.',
            'toStation.required' => 'Please enter a toStation.',

        );
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();
            return response()->json([
                'message' => 'Validation Error',
                'body' => $errors,
            ], 400);
        }

        $query = $request->query();

        return $this->tripService->getAllAvailableSeats($query['fromStation'], $query['toStation'], $query['tripId']);

    }

    public function reserve(Request $request)
    {
        $rules = array(
            'tripId' => 'required|integer',
            'fromStation' => 'required|integer',
            'toStation' => 'required|integer',
            'seatId' => 'required|integer',
        );
        $messages = array(
            'tripId.required' => 'Please enter a tripId.',
            'fromStation.required' => 'Please enter a fromStation.',
            'toStation.required' => 'Please enter a toStation.',
            'seatId.required' => 'Please enter a seatId.',
        );
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();
            return response()->json([
                'message' => 'Validation Error',
                'body' => $errors,
            ], 400);

        }
        $user = $request->user();
        $reservation = $this->tripService->reserveSeat($request->seatId, $request->fromStation, $request->toStation, $request->tripId, $user['id']);
        if ($reservation == null) {
            return response()->json([
                'error' => 'Seat Not Available',
            ], 400);
        }
        return $reservation;

    }
}
