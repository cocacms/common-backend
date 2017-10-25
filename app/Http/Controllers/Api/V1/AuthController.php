<?php
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Http\Response\ErrorResponse;
use App\Http\Response\SuccessResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Rojer\Wxxcx\Wxxcx;


class AuthController extends Controller
{
    public function login(Request $request,User $user,Wxxcx $wxxcx)
    {

        $this->validate($request, [
            'code' => 'required',
        ]);

        $code = $request->input('code');
        $loginInfo = $wxxcx->getLoginInfo($code); //code为用户登陆成功后获取到的
        if(!isset($loginInfo['openid'])){
            return new ErrorResponse($loginInfo['message']);
        }
        $user = $user->login($loginInfo['openid'],$loginInfo['session_key']);
        $token = Auth::guard('apiFront')->login($user);
        return new SuccessResponse($token);
    }

    public function updateInfo(Request $request,User $user)
    {
        $iv = $request->input('iv');
        $encryptedData = $request->input('encryptedData');
        $user->updateInfo($iv,$encryptedData);
        return new SuccessResponse();
    }
}