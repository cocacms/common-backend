<?php
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Http\Response\SuccessResponse;

class OptionsController extends Controller
{
    public function index()
    {
        return new SuccessResponse();
    }

}