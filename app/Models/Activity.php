<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;

class Activity extends Model
{
    use SoftDeletes;


    public function goods()
    {
        return $this->belongsToMany('App\Models\Good','activity_goods','activity_id','good_id');
    }

    public function goodsPK()
    {
        return $this->hasMany('App\Models\ActivityGood');
    }

    public function getMy($uid)
    {
        return Activity::query()->where('creator','=',$uid)
            ->orderBy('id', 'desc')
            ->with('goods')
            ->get()->map(function ($item){
            $item->isActive = (strtotime($item->start_time) <= time() && time() <= strtotime($item->end_time));
            return $item;
        });
    }

    public function createActivity($uid,$name, $start_time, $end_time,$goods)
    {
        $activity = new Activity();
        $activity->title = $name;
        $activity->creator = $uid;
        $activity->start_time = $start_time;
        $activity->end_time = $end_time;
        $activity->save();
        $data = [];
        foreach ($goods as $good){
            $data[] = [
                'activity_id' => $activity->id,
                'creator' =>   $uid,
                'good_id' => $good['id'],
                'count' => $good['count'],
                'origin_amount' => $good['origin_amount'],
            ];
        }

        ActivityGood::query()->insert($data);

        $ag = ActivityGood::query()->where('activity_id','=',$activity->id)->get();

        foreach ($ag as $_){
            $key = 'sell:'.$activity->id.':'.$_['id'];
            Redis::set($key,$_['count']);
            //推迟1天清掉缓存
            Redis::expireat( $key, strtotime($end_time) + (3600 * 24) );
        }

        return $activity;
    }
}
