<?php

namespace App\Models;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThreeUser extends Model
{
    use auth;
    use SoftDeletes;

    public function login($openid,$session_key = false)
    {
        $user = ThreeUser::query()->where('openid','=',$openid)->first();

        if($session_key){
            $user->sessionkey = $session_key;
            $user->save();
        }

        return $user;

    }

    public function xcxUpdateInfo($user,$iv,$encryptedData)
    {
        $miniProgram = EasyWeChat::miniProgram();
        $userinfo = $miniProgram->encryptor->decryptData($user->sessionkey, $iv, $encryptedData);
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
