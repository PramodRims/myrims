<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function edit(Request $request, $course)
    {
        return $course;
    }
}
