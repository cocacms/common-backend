<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    protected $pageSize = 10;
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function handleFilter($class,$filter){
        $class = new $class();
        if(empty($filter)) return $class;
        $filter = json_decode($filter,true);
        $filter = collect($filter)->filter(function ($value, $key){
            return !empty($value);
        });
        foreach ($filter as $key =>$value){
            if(is_array($value)){
                $class = $class->whereBetween($key,$value);
            }else{
                $class = $class->where($key,'=',$value);
            }
        }
        return $class;
    }

    public function user()
    {
        return \Illuminate\Support\Facades\Auth::guard('apiFront')->user();

    }
}
