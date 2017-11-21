<?php
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

namespace App\Http\Controllers\Api\V1\Admin;


use App\Http\Controllers\Controller;
use App\Http\Response\SuccessResponse;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class PermissionController extends Controller
{
    public function lists($role_id)
    {
        $permissionList = Role::query()->findOrFail($role_id)->permissions->map(function ($item){
            return $item->getTag();
        })->toArray();

        $routes = Route::getRoutes()->getRoutes();
        $routeList = [];
        foreach ($routes as $route){
            if(!is_null($route->permissionName)){

                $uri = $route->uri();
                $method = $route->methods()[0];
                $permission = strtolower($uri.'@@@'.$method);

                $routeList[$route->groupName][] = [
                    'name'=>$route->permissionName ,
                    'uriWithMethod' => $permission ,
                    'can' => in_array($permission,$permissionList) ? true : false
                ];
            }
        }

        return new SuccessResponse($routeList);
    }

    public function my(Permission $permission)
    {


        $links = [];
        $allPermission = [];

        $permissionList = $permission->myPermission()->map(function ($item) use ($links){
            $permission = strtolower($item->uri.'@@@'.$item->method);
            return $permission;
        })->all();



        $routes = Route::getRoutes()->getRoutes();

        $supper = Auth::user()->supper == 1;
        foreach ($routes as $route){

            if(!is_null($route->permissionName)){
                $uri = $route->uri();
                $method = $route->methods()[0];
                $permission_tag = strtolower($uri.'@@@'.$method);
                if($route->link){
                    $links[$permission_tag] = $route->link;
                }

                if($supper){
                    $allPermission[] = [
                        'permission' => $route->getName(),
                        'menu' => $route->link ?? null
                    ];
                    continue;
                }

                if($route->autoPermission){
                    $allPermission[] = [
                        'permission' => $route->getName(),
                        'menu' => in_array($permission_tag,$permissionList) && $route->link ? $route->link : null
                    ];

                    continue;
                }

                if (in_array($permission_tag,$permissionList)){
                    $allPermission[] = [
                        'permission' => $route->getName(),
                        'menu' => $route->link ?? null
                    ];
                    continue;
                }


            }


        }



        return new SuccessResponse( $allPermission );

    }


    public function adjust($id,Permission $permission,Request $request)
    {
        $add = $request->input('diff.add',[]);
        $remove = $request->input('diff.remove',[]);

        $permission->adjust($id,$add,$remove);

        return new SuccessResponse();

    }

}