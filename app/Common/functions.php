<?php

if(!function_exists('captcha_check'))
{
    /**
     * 验证验证码
     * @param $captcha
     * @return bool
     */
    function captcha_check($captcha)
    {
        $_captcha = session('_captcha');
        $captchaBuild = new \Gregwar\Captcha\CaptchaBuilder();
        $captchaBuild->setPhrase($_captcha);
        return $captchaBuild->testPhrase($captcha);
    }
}

if(!function_exists('format_time'))
{
    /**
     * 时间格式化 默认当前时间
     * @param null $format
     * @param null $time
     * @return false|string
     */
    function format_time($format = null,$time = null){
        $time = $time === null ? time() : $time;
        $format = $format === null ? 'Y-m-d H:i:s' : $format;
        return date($format,$time);
    }
}
if(!function_exists('time_to_zero'))
{
    /**
     * 转为凌晨0点的时间戳
     * @param $time
     * @return false|int
     */
    function time_to_zero($time){
        return strtotime(date('Y-m-d 00:00:00',strtotime($time)));
    };
}


if(!function_exists('get_error_response_exception'))
{

    function get_error_response_exception($message,$code = -1){
        return new \Illuminate\Http\Exceptions\HttpResponseException((new \App\Http\Response\ErrorResponse($message,$code))->toResponse(app('request')));
    }
}


if(!function_exists('hasRoutePermission'))
{
    /**
     * 验证用户是否具某个路由的权限
     * @param $route
     * @return bool
     * @throws \App\Exceptions\UndefinedRouteException
     */
    function hasRoutePermission($route){

        $allPermissionUri = [];

        if (!$route instanceof \App\Services\Route\Route){
            $routeCollection = \Illuminate\Support\Facades\Route::getRoutes();
            $routeName = $route;
            $route = $routeCollection->getByName($route);
            if ($route == null){
                throw new \App\Exceptions\UndefinedRouteException($routeName);
            }
        }


        $uri = $route->uri();
        $methods = $route->methods();

        $user = \Illuminate\Support\Facades\Auth::user();

        //无需验证权限的直接放行
        if ($user && $route->autoPermission){
            return true;
        }
        //超级管理员不验证权限
        if($user->supper == 1){
            return true;
        }
        //获取用户的全部权限
        $roles = $user->roles;
        foreach ($roles as $role) {
            $permissions = $role->permissions;
            foreach ($permissions as $permission){
                $allPermissionUri[] = strtolower($permission->uri.'@@@'.$permission->method);
            }
        };
        if(in_array(strtolower($uri.'@@@'.$methods[0]),$allPermissionUri)){
            return true;
        }else{
            return false;
        }
    }
}

if (!function_exists('order_id_create'))
{
    function order_id_create(){
        $uuid = Ramsey\Uuid\Uuid::uuid4();
        return date('YmdHis') . $uuid->getTimeLowHex();
    }
}