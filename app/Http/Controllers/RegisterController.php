<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class RegisterController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

/**
 * @OA\Post(
 * path="/api/login",
 * summary="Sign in",
 * description="Login by email, password",
 * operationId="authLogin",
 * tags={"auth"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       required={"email","password"},
 *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
 *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
 *    ),
 * ),
 *
 *  @OA\Response(
 *    response=200,
 *    description="success",
 * @OA\MediaType(
 *              mediaType="application/json",
 *          )
 * ),
 * @OA\Response(
 *    response=400,
 *    description="Bad Request response",
 * @OA\MediaType(
 *              mediaType="application/json",
 *          )
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 * @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *     )
 * )
 *  @OA\Post(
 * path="/api/register",
 * summary="sign up",
 * description="Login by email, password",
 * operationId="authLogin",
 * tags={"auth"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       required={"name","email","password","c_password"},
 *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
 *       @OA\Property(property="name", type="string", example="Robustan"),
 *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
 *       @OA\Property(property="re_password", type="string", format="password", example="PassWord12345"),
 *
 *    ),
 * ),
 * @OA\Response(
 *    response=400,
 *    description="Bad Request",
 * @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *     ),
 * @OA\Response(
 *    response=200,
 *    description="Registered successfully",
 * @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *     )
 * )
 */

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            're_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'body' => $validator->errors(),
            ], 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

        return response()->json([
            'body' => $success,
            'message' => 'User registered successfully.',
        ], 200);

    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'body' => $validator->errors(),
            ], 400);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['name'] = $user->name;

            return response()->json([
                'body' => $success,
                'message' => 'User login successfully.',
            ], 200);

        } else {
            return response()->json([
                'error' => 'Wrong credentials',
            ], 422);

        }
    }
}
