<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    //


    public function getMy($uid)
    {
        return Good::query()->where('creator','=',$uid)->get();
    }

    public function createGood($name,$uid,$pic,$price)
    {
        $good = new Good();
        $good->name = $name;
        $good->creator = $uid;
        $good->pic = $pic;
        $good->origin_amount = $price;
        $good->save();
        return $good;
    }

    public function search($key)
    {
        return Good::query()->where('name','like',"%$key%")->get();
    }

    public function getByIds($ids)
    {
        return Good::query()->whereIn('id',$ids)->get();
    }
}
