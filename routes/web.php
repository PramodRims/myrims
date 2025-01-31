<?php

use App\Http\Controllers\ImportController;
use App\Models\Course;
use App\Models\CourseHasStudent;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    // $data = Course::with('instructor', 'students')->first();

    // dd($data);
    $courseId = 1;


    $data = Course::find($courseId)->students;

    // $data = User::whereHas('courses', function ($query) use ($courseId) {
    //     $query->where('course_id', $courseId);
    // })
    // ->whereHas('roles', function ($query) {
    //     $query->where('first_name', 'student'); // Ensuring the user has the role of 'student'
    // })
    // ->pluck('first_name', 'id');

    dd($data);
    return view('welcome');
});

// Route::group(['prefix' => 'admin/courses/', 'as' => 'admin.'], function () {
//     Route::get('{course}/edit', [ImportController::class, 'edit'])->name('courses.edit');
// });

Route::get('/admin/courses/{course}/edit', [ImportController::class, 'edit']);
