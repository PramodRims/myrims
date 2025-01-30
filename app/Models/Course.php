<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;


    protected $guarded = [];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_has_students', 'course_id', 'student_id');
    }

    public function studentData()
{
    return CourseHasStudent::where('course_id', $this->id)
        ->with('student') // Eager load student with specific fields
        ->get()
        ->pluck('student');
}
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
