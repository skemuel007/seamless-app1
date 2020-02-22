<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class CourseRegistrationController extends Controller
{
    //
    public function registerCourses(Request $request) {
       // validate request parameter
       $validator = Validator::make($request->all(), [
           'userId' => 'required',
           'courseId' => 'required',
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


    }
}
