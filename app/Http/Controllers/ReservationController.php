<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\TripServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Get(
 * path="/api/seats/availableseats",
 * summary="available seats",
 * description="get seats of a trip and their availability",
 * security={ {"bearerAuth": {} }},
 * tags={"seats"},
 *
 * @OA\Parameter(
 *         name="tripId",
 *         in="query",
 *         description="Id of The Trip to Check",
 *         required=true,
 *      ),
 *
 * @OA\Parameter(
 *         name="fromStation",
 *         in="query",
 *         description="Id of The From Station",
 *         required=true,
 *      ),
 * @OA\Parameter(
 *         name="toStation",
 *         in="query",
 *         description="Id of The To Station",
 *         required=true,
 *      ),
 *  @OA\Response(
 *    response=200,
 *    description="success",
 *    @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *  ),
 * @OA\Response(
 *    response=400,
 *    description="Bad Request response",
 *    @OA\MediaType(
 *              mediaType="application/json",
 *          )
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *     )
 * )
 *@OA\Post(
 * path="/api/seats/reserveseat",
 * summary="reserve seats",
 * description="reserve a seat on a trip from a station to a station",
 * security={ {"bearerAuth": {} }},
 * tags={"seats"},
 *  @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       required={"seatId","fromStation","toStation","tripId"},
 *       @OA\Property(property="seatId", type="integer", example="1343"),
 *       @OA\Property(property="fromStation", type="integer", example="1343"),
 *       @OA\Property(property="toStation", type="integer", example="1343"),
 *       @OA\Property(property="tripId", type="integer", example="1343")
 *    ),
 *),
 *  @OA\Response(
 *    response=201,
 *    description="success",
 *    @OA\MediaType(
 *              mediaType="application/json",
 *          ) * ),
 * @OA\Response(
 *    response=400,
 *    description="Bad Request response",
 *    @OA\MediaType(
 *              mediaType="application/json",
 *          )
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *     )
 * )
 *
 */

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
