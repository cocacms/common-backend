<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\TransformsRequest;

class RemoveNull extends TransformsRequest
{
    protected function cleanArray(array $data)
    {
        return collect($data)->filter(function ($value, $key) {
            return !is_null($value);
        })->all();
    }

}
