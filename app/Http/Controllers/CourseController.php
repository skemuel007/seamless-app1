<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessCourse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    //
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
}
