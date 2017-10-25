<?php
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

namespace App\Http\Controllers\Api\V1\Admin;


use App\Http\Response\SuccessResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ConfigController
{
    public function website()
    {
        $data = Cache::store('database')->get('website',[]);
        return new SuccessResponse($data);
    }

    public function updateWebsite(Request $request)
    {
        $data = $request->all();
        Cache::store('database')->forever('website',$data);
        return new SuccessResponse();
    }

}