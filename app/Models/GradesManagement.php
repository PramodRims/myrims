<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradesManagement extends Model
{
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function files()
    {
        return $this->morphMany(Media::class, 'mediaable');
    }
}
