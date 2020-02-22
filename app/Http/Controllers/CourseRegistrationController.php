<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class CourseRegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * Resource allows users to register for courses
     * @param Request $request
     * @return JsonResponse
     */
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

        // get user details
        $user = User::findOrFail($request->input('userId'));
        $course = Course::findOrFail($request->input('courseId'));

        // confirm if user is registered for the course
        if ( $user->courses()->get()->contains($course) ) {
            // responsed with 409 conflict
            return response()->json([
                'message' => 'Course already registered',
                'data' => null
            ], JsonResponse::HTTP_CONFLICT);
        }

        // else save the record
        $user->courses()->save($course);

        // return json response 201
        return response()->json([
            'message' => 'Course registered',
            'data' => null
        ], JsonResponse::HTTP_CREATED);
    }
}
