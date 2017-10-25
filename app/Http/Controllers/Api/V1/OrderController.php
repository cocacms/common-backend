<?php
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Http\Response\SuccessResponse;
use App\Models\Order;
use App\Models\Task;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function sell()
    {

        $userID = $this->user()->id;
        $data = Order::query()->where('seller','=',$userID)
            ->with(['goodsPK.goods'
                => function($query){
                    $query->withTrashed();
                }
                ,'seller','buyer'])
            ->orderBy('id','DESC')
            ->get();

        return new SuccessResponse($data);
    }

    public function buy()
    {
        $userID = $this->user()->id;

        $data = Order::query()->where('creator','=',$userID)
            ->with(['goodsPK.goods'
                => function($query){
                    $query->withTrashed();
                }
                ,'seller','buyer'])
            ->orderBy('id','DESC')
            ->get();

        return new SuccessResponse($data);
    }

    public function check($tid)
    {
        return new SuccessResponse(Task::query()->where('tid','=',$tid)->firstOrFail());
    }
}