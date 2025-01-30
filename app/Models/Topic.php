<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    protected $table = 'topics';

    protected $guarded = [];
    use SoftDeletes;  // Add this line

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function files()
    {
        return $this->morphMany(Media::class, 'mediaable');
    }
}
