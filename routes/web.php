<?php

use App\Http\Controllers\ImportController;
use App\Models\Course;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $data = Course::with('instructor', 'students')->first();

    dd($data);
    return view('welcome');

    Route::get('/import', [ImportController::class, 'import'])->name('your.import.route');

});
