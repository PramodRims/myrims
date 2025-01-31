<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchHasStudent extends Model
{
    protected $guarded = [];
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class);
    }
}
