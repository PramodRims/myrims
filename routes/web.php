<?php

use App\Http\Controllers\ImportController;
use App\Models\Course;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $data = Course::with('instructor', 'students')->first();

    dd($data);
    return view('welcome');
});

// Route::group(['prefix' => 'admin/courses/', 'as' => 'admin.'], function () {
//     Route::get('{course}/edit', [ImportController::class, 'edit'])->name('courses.edit');
// });

Route::get('/admin/courses/{course}/edit', [ImportController::class, 'edit']);
