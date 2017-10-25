<?php
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Http\Response\SuccessResponse;
use App\Models\Good;
use Illuminate\Http\Request;

class GoodController extends Controller
{
    public function my(Good $good)
    {
        $user = $this->user();
        return new SuccessResponse($good->getMy($user->id));
    }

    public function create(Request $request,Good $good)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'pic' => 'required',
            'price' => 'required',
        ]);

        $user = $this->user();

        $name = $data['name'];
        $uid = $user->id;
        $pic = $data['pic'];
        $price = $data['price'];
        return new SuccessResponse($good->createGood($name,$uid,$pic,$price));

    }

    public function search(Request $request ,Good $good)
    {
        $key = $request->input('key');
        return new SuccessResponse($good->search($key));
    }

    public function byIds(Request $request ,Good $good)
    {
        $ids = $request->input('ids');
        return new SuccessResponse($good->getByIds(explode(',',$ids)));
    }

}