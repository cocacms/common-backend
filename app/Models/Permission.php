<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Permission extends Model
{
    public function myPermission()
    {
        $roles = Auth::user()->roles;
        $permissions = [];

        foreach ($roles as $role){

            foreach ($role->permissions as $permission_item){
                $permissions[] = $permission_item;
            }
        }

        return collect($permissions)->unique(function ($item) {
            return $item->getTag();
        })->values();

    }

    public function getTag()
    {
        return strtolower($this->uri.'@@@'.$this->method);
    }

    public function adjust($role_id,$add,$remove){
        //add
        $insert_data = [];
        foreach ($add as $item) {
            $uri_method = explode('@@@',$item,2);
            if (count($uri_method) >= 2){
                $insert_data[] = [
                    'role_id' => $role_id,
                    'uri' => $uri_method[0],
                    'method' => $uri_method[1],
                    'created_at' => format_time(),
                    'updated_at' => format_time()
                ];
            }
        }
        Permission::query()->insert($insert_data);

        //remove
        if (count($remove) == 0) return;

        $permission = Permission::query()
            ->where('role_id','=',$role_id);

        $permission->where(function ($query) use ($remove){
            foreach ($remove as $item) {
                $uri_method = explode('@@@',$item,2);
                if (count($uri_method) >= 2){
                    $query->orWhere(function ($query)use ($uri_method){
                        $query->where([
                            ['uri','=',$uri_method[0]],
                            ['method','=',$uri_method[1]]
                        ]);
                    });
                }
            }
        });

        $permission->delete();


    }
}
