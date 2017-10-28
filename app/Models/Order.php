<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    protected $fillable = ['cityName','countyName','detailInfo','postalCode','provinceName','telNumber','userName'];

    public function goods()
    {
        return $this->belongsToMany('App\Models\Good','order_goods','order_id','good_id');
    }

    public function goodsPK()
    {
        return $this->hasMany('App\Models\OrderGood');
    }

    public function seller()
    {
        return $this->hasOne('App\Models\User','id','seller');
    }

    public function buyer()
    {
        return $this->hasOne('App\Models\User','id','creator');
    }


    public function activity()
    {
        return $this->hasOne('App\Models\Activity','id','activity_id');
    }



    public function createOrder($goodIds,$creator,$aid,&$o = null,&$price = 0)
    {

        Log::info('create order Doing: goods => ['.implode(',',$goodIds).'] creator => '.$creator);
        try{
            $goods = ActivityGood::query()->whereIn('id',$goodIds)->with('goods')->get();

            $order = Order::query()->where('activity_id','=',$aid)
                ->where('creator','=',$creator)
                ->first();
            if(is_null($order)){
                $order = new Order();
                $order->oid = order_id_create();
                $order->creator = $creator;
                $order->seller = 0;
                $order->activity_id = $aid;
                $order->save();
            }

            $data = [];
            $title = [];
            $gid = [];
            foreach ($goods as $good){
                $gid[] = $good->goods->id;
                $data[] = [
                    'order_id'      => $order->id,
                    'good_id'       => $good->goods->id,
                    'count'         => 1,
                    'origin_amount' => $good->origin_amount
                ];
                $price += $good->origin_amount;
                $title[] = $good->goods->name;
                $seller = $good->creator;
            }


            //获取已经有的
            $has = OrderGood::query()->where('order_id','=',$order->id)
                ->whereIn('good_id',$gid)
                ->get();
            //已经有的数量+1

            $hasId = [];
            foreach ($has as $_){
                $_->increment('count');
                $hasId[] = $_->good_id;
            }

            //获取已经有的

            $data = array_filter($data,function ($item) use ($hasId){
                return !in_array($item['good_id'],$hasId);
            });

            OrderGood::query()->insert($data);

            $order->seller = $seller;
            $order->save();
            $o = $order;
            return implode('、',$title);
        }catch (\Exception $e){
            Log::info('create order error: goods => ['.implode(',',$goodIds).'] creator => '.$creator);
            return '';
        }
    }
}
