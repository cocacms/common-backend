<?php

namespace App\Jobs;

use App\Models\Activity;
use App\Models\Order;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Redis;

class Miao implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $ids;
    private $aid;
    private $creator;
    private $tid;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ids,$aid,$creator,$tid)
    {
        $this->ids = $ids;
        $this->aid = $aid;
        $this->creator = $creator;
        $this->tid = $tid;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $task = Task::query()->where('tid',$this->tid)->first();
        if(!is_null($task)){
            $activity = Activity::query()->findOrFail($this->aid);
            if(!(strtotime($activity->start_time) <= time() && time() <= strtotime($activity->end_time))){
                return;
            }
            $ok = [];
            foreach ($this->ids as $id){
                $key = 'sell:'.$this->aid.':'.$id;
                if(Redis::EXISTS($key) == 1){
                    $count = Redis::DECR($key);
                    if($count >= 0){
                        $ok[] = $id;
                    }
                }

            }


            if(count($ok) == 0){
                $task->finish = true;
                $task->save();
                $task->result = '很遗憾，没有秒到您要的商品了！';
                return;
            }
            $order = new Order();
            $task->finish = true;
            $task->save();
            $task->result = '恭喜您成功秒到了：'. $order->createOrder($ok,$this->creator,$this->aid);

            $task->finish = true;
            $task->save();
        }

    }
}
