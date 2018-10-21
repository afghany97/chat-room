<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }
}
