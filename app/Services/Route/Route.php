<?php
namespace App\Services\Route;


use App\Exceptions\UndefinedRouteException;

class Route extends \Illuminate\Routing\Route{
    public $permissionName;
    public $autoPermission = false;
    public $link;
    public $groupName;

    public function permissionName($name){
        $this->permissionName = $name;
        $this->groupName = \Illuminate\Support\Facades\Route::getCurrentGroupName();
        return $this;
    }

    public function autoPermission(){
        $this->autoPermission = true;
        return $this;
    }

    public function bindMenu($link){
        $this->link = $link;
        return $this;
    }
}
