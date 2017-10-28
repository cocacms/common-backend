<?php

namespace App\Models;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class User extends Model implements Authenticatable
{
    use auth;


    public function login($openid,$session_key)
    {
        $user = User::query()->where('openid','=',$openid)->first();

        if(is_null($user)){
            $user = new User();
            $user->openid = $openid;
        }

        $user->sessionkey = $session_key;
        $user->save();

        return $user;
        
    }

    public function updateInfo($iv,$encryptedData)
    {
        $user = \Illuminate\Support\Facades\Auth::guard('apiFront')->user();
        $xcx = App::make("wxxcx");
        $xcx->setSessionKey($user->sessionkey);
        $userinfo = $xcx->getUserInfo($encryptedData,$iv);
//        Log::info('user info update '.json_encode($userinfo));
        if(!isset($userinfo['nickName'])){
            throw new AuthenticationException();
        }
        $user->nickName = $userinfo['nickName'];
        $user->gender = $userinfo['gender'];
        $user->city = $userinfo['city'];
        $user->province = $userinfo['province'];
        $user->country = $userinfo['country'];
        $user->avatarUrl = $userinfo['avatarUrl'];
        $user->save();
    }
}
