<?php

namespace App\Http\Controllers;

use App\Course;
use App\Http\Resources\CourseRegistrationResource;
use App\Jobs\ProcessCourse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct()
    {
        // add middle ware for authentication and authorization
        $this->middleware('jwt.auth');
    }

    /**
     * Controller method that creates course
     * @return \Illuminate\Http\JsonResponse
     */
    public function createCourse() {
       // create job queue with a 5 seconds delay
        $createCourseJob = (new ProcessCourse())
            ->delay(Carbon::now()->addSeconds(5))->onQueue('processing');
        $this->dispatch($createCourseJob); // dispatch the job

        // return response
        return response()->json([
            'message' => 'Courses created',
            'data' => []
        ]);
    }

    public function allCoursesAndRegistrations()
    {
        // retrieve all courses
        $coursesWithTheirRegistrations = Course::with('users')->get();

        // return json response with 200 ok status
        return response()->json([
            'message' => 'Courses with their registrations',
            'data' => CourseRegistrationResource::collection($coursesWithTheirRegistrations)
        ], 200);
    }

    public function exportCourses() {

    }


}
