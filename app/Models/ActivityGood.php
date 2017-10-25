<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityGood extends Model
{
    public function goods()
    {
        return $this->hasOne('App\Models\Good','id','good_id');
    }
}
