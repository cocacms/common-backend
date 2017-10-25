<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Good extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','creator','pic','origin_amount'];


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

    public function updateGood($id,$data)
    {
        $good = Good::query()->findOrFail($id);
        $good->fill($data);
        $good->save();
        return $good;
    }

    public function removeGood($ids)
    {
        $count = Good::query()->whereIn('id',$ids)->delete();
        return $count;
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
