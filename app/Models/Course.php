<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;


    protected $guarded = [];

    public function head()
    {
        return $this->belongsTo(User::class, 'head_id');
    }

    // new
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function students()
    {
        return $this->hasManyThrough(
            User::class,  // The related model (students)
            BatchHasStudent::class, // The intermediate model (batches)
            'course_id',  // Foreign key on the Batch model (the column in the batches table that references the Course model)
            'id',          // Foreign key on the User model (the column in the users table that references the student)
            'id',          // Local key on the Course model (the column in the courses table that references the Batch model)
            'id'     // Local key on the Batch model (the column in the batch_has_students pivot table that references the student)
        );
    }

    //     public function students()
    //     {
    //         return $this->belongsToMany(User::class, 'course_has_students', 'course_id', 'student_id');
    //     }

    //     public function studentData()
    // {
    //     return CourseHasStudent::where('course_id', $this->id)
    //         ->with('student') // Eager load student with specific fields
    //         ->get()
    //         ->pluck('student');
    // }
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    
}
