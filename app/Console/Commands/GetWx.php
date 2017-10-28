<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GetWx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wx:access_token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get wx access_token';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info('get access_token ...');

        $curl = curl_init();

        $appid = config('wxxcx.appid');
        $secret = config('wxxcx.secret');

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            Log::info("cURL Error #:" . $err);
        } else {
            $response = json_decode($response);
            if($response->access_token){
                $this->info('get success : '.$response->access_token);
                Cache::forever('wx_access_token', $response->access_token);
            }
        }
    }
}
