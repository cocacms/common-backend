<?php
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

namespace App\Http\Controllers\Api\V1\Admin;


use App\Http\Controllers\Controller;
use App\Http\Response\SuccessResponse;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function lists(Request $request)
    {
        $pageSize = $request->input('pageSize',$this->pageSize);
        $data = Order::query()
            ->with(['goodsPK.goods' => function($query) {
                $query->withTrashed();
            },'seller','buyer','activity'])
            ->orderBy('id','DESC')->paginate($pageSize);
        return new SuccessResponse($data);
    }

}