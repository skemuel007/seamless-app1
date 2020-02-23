<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;


/**
 * Class AuthController
 * @package App\Http\Controllers
 *
 * @author Stanley-Kemuel Lloyd Salvation
 */
class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login', 'register']]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/login",
     *     tags={"login"},
     *     summary="End point allows user to login",
     *     operationId="login",
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Request parameter validation error"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful"
     *     ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="Email Address of user",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Password of user",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function login(Request $request) {
        // validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required'
        ]);

        // check for request validation error
        if( $validator->fails() ) {
            // return json response with status code 422
            return response()->json(
                [
                    'message' => 'Request validation error',
                    'error' => $validator->errors()
                ],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        // credentials array
        $credentials = $request->only('email', 'password');

        // check for credentials and build claims
        if ( !$token = auth()->attempt($credentials)) {
            // if credentials not found return json status 401 unauthorized
            return response()->json([
                'message' => 'Invalid credentials',
                'error' => 'Unauthorized'
            ],
                JsonResponse::HTTP_UNAUTHORIZED
                );
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $token
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/register",
     *     tags={"register"},
     *     summary="End point allows user a to create an account",
     *     operationId="login",
     *     @OA\Response(
     *         response=422,
     *         description="Request parameter validation error"
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User Created"
     *     ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     description="Name of user",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="Email Address of user",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Password of user",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function register(Request $request) {
        // validate request parameters
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required',
            'password' => 'required'
        ]);

        // check for validation failure
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Request validation error',
                'error' => $validator->errors()
            ],
                422);
        }

        // create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // return json response with status 201 created
        return response()->json([
            'message' => 'User created',
            'data' => null
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request) {
        auth()->login(true); // force user token to blacklist
        //
        return response()->json([
            'message' => 'User logout',
            'data' => null,
        ], 200);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        return response()->json(auth()->user());
    }

}
