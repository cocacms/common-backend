<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendWxTmp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $id;
    private $openid;
    private $form_id;
    private $oid;
    private $nickName;
    private $count;
    private $price;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id,$openid,$form_id,$oid,$nickName,$count,$price)
    {
        $this->id = $id;
        $this->openid = $openid;
        $this->form_id = $form_id;
        $this->oid = $oid;
        $this->nickName = $nickName;
        $this->count = $count;
        $this->price = $price;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $wx_access_token = Cache::get('wx_access_token');

        $TID = 'GeW2Y3ZnSypXySpFwRJWhXoRpxooWi0T4-f0zj1nFXc';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=$wx_access_token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'touser'=>$this->openid,
                'template_id'=>$TID,
                'page'=>'pages/me/index?show=1&oid=' . $this->id ,
                'form_id'=>$this->form_id,
                'data'=>[
                    'keyword1'=>[
                        "value"=> $this->oid,
                    ],
                    'keyword2'=>[
                        "value"=> $this->nickName,
                    ],
                    'keyword3'=>[
                        "value"=> $this->count,
                    ],
                    'keyword4'=>[
                        "value"=> $this->price,
                    ],
                ],
                'emphasis_keyword'=>'keyword4.DATA'
            ]),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            Log::info("send tmp error: cURL Error #:" . $err) ;
        } else {
            $response = json_decode($response);
            if($response->errcode != 0){
                Log::info("send tmp error: " . $response->errmsg) ;
            }

        }
    }
}
