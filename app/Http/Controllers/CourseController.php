<?php

namespace App\Http\Controllers;

use App\Course;
use App\Exports\CourseExport;
use App\Http\Resources\CourseRegistrationResource;
use App\Jobs\ProcessCourse;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Exception;

class CourseController extends Controller
{
    private $excel;

    public function __construct(Excel $excel)
    {
        // add middle ware for authentication and authorization
        $this->middleware('jwt.auth');
        $this->excel = $excel;
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
        // generate random file name
        $fileName = 'courses' . Str::random(16) . '.csv';
        try {
            /*$this->excel->store( new CourseExport, $fileName, 'public');

            $url = Storage::url($fileName);
            return response()->json([
                'message' => 'File retrieved',
                'data' => $url
            ], 200);

            $path = storage_path('app/public' . DIRECTORY_SEPARATOR . $fileName);

            // check if file exists
            if( !File::exists($path)) {
                return response()->json([
                    'message' => 'Request file download error',
                    'data' => null
                ], 404);
            }*/

            // return download response
            return $this->excel->download(new CourseExport, 'courses.csv');

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => null,
            ]);
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => null,
            ]);
        }
    }


}
