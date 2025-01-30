<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Media extends Model
{
    protected $guarded = [];
    use SoftDeletes;


    public function mediaable()
    {
        return $this->morphTo();
    }
}
