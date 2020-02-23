<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('register', 'AuthController@register')->name('login');
Route::post('login', 'AuthController@login')->name('register');
Route::get('user/profile', 'AuthController@profile');


Route::group([
    'prefix' => 'course'
], function() {
    Route::post('create', 'CourseController@createCourse');
    Route::post('register', 'CourseRegistrationController@registerCourses');
    Route::get('/', 'CourseController@allCoursesAndRegistrations');

    Route::group([
        'prefix' => 'export'
    ], function() {
        Route::get('csv', 'CourseController@exportCoursesAsCSV')->name('exportCoursesCSV');
        Route::get('excel', 'CourseController@exportCoursesAsExcelNative')->name('exportCoursesExcelNative');
    });

});
