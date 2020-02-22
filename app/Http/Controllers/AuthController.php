<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
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
    public function me()
    {
        return response()->json(auth()->user());
    }

}
