<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{


    public function permissions()
    {
        return $this->hasMany('App\Models\Permission');
    }

    public function addRole($name)
    {
        $role = new Role();
        $role->name = $name;
        $role->save();
        return $role;
    }

    public function updateRole($id,$name)
    {
        $role = Role::query()->findOrFail($id);
        $role->name = $name;
        $role->save();
        return $role;
    }

    public function removeRole($id)
    {
        RoleMemberRelation::query()->where('role_id','=',$id)->delete();
        Permission::query()->where('role_id','=',$id)->delete();
        return Role::query()->findOrFail($id)->delete();
    }
}
