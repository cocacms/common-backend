<?php
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

namespace App\Http\Controllers\Api\V1\Admin;


use App\Http\Controllers\Controller;
use App\Http\Response\SuccessResponse;
use App\Models\Good;
use Illuminate\Http\Request;

class GoodController extends Controller
{
    public function lists(Request $request)
    {
        $pageSize = $request->input('pageSize',$this->pageSize);
        $key = $request->input('key','');
        $good = Good::query();
        if($key != ''){
            $good = $good->where('name','like',"%$key%");
        }
        $data = $good->paginate($pageSize);
        return new SuccessResponse($data);
    }

    public function create(Request $request,Good $good)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'pic' => 'required',
            'origin_amount' => 'required',
        ]);

        $name = $data['name'];
        $uid = -1;
        $pic = $data['pic'];
        $price = $data['origin_amount'];
        return new SuccessResponse($good->createGood($name,$uid,$pic,$price));

    }

    public function update(Request $request,$id,Good $good)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'pic' => 'required',
            'origin_amount' => 'required',
        ]);

        $data['creator'] = 0;
        $good->updateGood($id,$data);

        return new SuccessResponse();
    }


    public function delete(Request $request,Good $good)
    {
        $id = $request->input('id',null);
        if(!is_null($id)){
            $ids = [$id];
        }else{
            $ids = $request->input('ids',[]);
        }

        $good->removeGood($ids);
        return new SuccessResponse();
    }

}