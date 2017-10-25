<?php
/**
 * Coca-Admin is a general modular web framework developed based on Laravel 5.4 .
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 * Git:        https://github.com/rojer95/CocaAdmin
 * QQ Group:   647229346
 */

namespace App\Http\Middleware;

use App\Http\Response\ErrorResponse;
use Closure;
use Illuminate\Support\Facades\Route;

class PermissionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $route = Route::getCurrentRoute();
        if(hasRoutePermission($route)){
            return $next($request);
        }else{
            return (new ErrorResponse('你没有操作权限！'))->toResponse();
        }
    }
}