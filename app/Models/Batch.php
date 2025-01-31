<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    protected $guarded = [];

    use SoftDeletes;

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function students()
    {
        return $this->belongsToMany(User::class, 'batch_has_students', 'batch_id', 'student_id');
    }
}
