<?php
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Http\Response\ErrorResponse;
use App\Http\Response\SuccessResponse;
use App\Jobs\Miao;
use App\Models\Activity;
use App\Models\Order;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Ramsey\Uuid\Uuid;

class ActivityController extends Controller
{
    public function my(Activity $activity)
    {
        $user = $this->user();
        return new SuccessResponse($activity->getMy($user->id));
    }

    public function create(Request $request,Activity $activity)
    {
        $data = $this->validate($request, [
            'title' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'goods' => 'required'
        ]);
        $user = $this->user();

        $result = $activity->createActivity($user->id,$data['title'],$data['start_time'],$data['end_time'],$data['goods']);

        return new SuccessResponse($result);

    }

    public function detail($id)
    {
        $activity = Activity::query()->with('goodsPK.goods')->findOrFail($id);

        $activity->isActive = (strtotime($activity->start_time) <= time() && time() <= strtotime($activity->end_time));
        $activity->start_time = strtotime($activity->start_time) * 1000;
        $activity->end_time = strtotime($activity->end_time) * 1000;

        $activity->goods = array_values( $activity->goodsPK->filter(function ($good, $key) use ($activity) {

            if(is_null($good->goods)){
                return false;
            }

            $key = 'sell:'.$activity->id.':'.$good->id;
            $count = Redis::get($key);

            if(is_null($count) || $count < 0){
                $count = 0;
            }
            $good->sell_count = $count;

            return true;
        })->toArray() );

        return new SuccessResponse($activity);


    }

    public function handle(Request $request)
    {
        $userId = $this->user()->id;
        $ids = $request->input('ids');
        $aid = $request->input('aid');
        $fid = $request->input('fid');

        $activity = Activity::query()->findOrFail($aid);
        if(!(strtotime($activity->start_time) <= time() && time() <= strtotime($activity->end_time))){
            return new ErrorResponse('目前不在秒杀的时段内！');
        }

        $ok = [];
        foreach ($ids as $id){
            $key = 'sell:'.$aid.':'.$id;
            if(Redis::EXISTS($key) == 1 && intval(Redis::GET($key)) > 0){
                $ok[] = $id;
            }

        }

        if(count($ok) == 0){
            return new SuccessResponse('');
        }

        //创建任务
        $task = new Task();
        $uuid = Uuid::uuid4();
        $task->oid = 0;
        $task->form_id = $fid;
        $task->tid = $uuid->getTimeLowHex();
        $task->save();

        Miao::dispatch($ids,$aid,$userId,$task->tid);

        return new SuccessResponse($task->tid);

    }
}