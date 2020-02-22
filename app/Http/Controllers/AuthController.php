<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller
{
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

    public function register() {

    }
}
