<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function order()
    {
        return $this->hasOne('App\Models\Order','id','oid');
    }
}
