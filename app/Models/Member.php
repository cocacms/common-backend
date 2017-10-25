<?php

namespace App\Models;

use App\Http\Response\ErrorResponse;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

class Member extends Model implements Authenticatable
{
    use auth;
    use SoftDeletes;

    protected $fillable = ['username','password','avatar','nickname','sex','tel','birthday','mail'];
    /**
     * 属于该用户的身份。
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role','role_member_relations');
    }

    public function addMember($data){
        $data['password'] = Hash::make($data['password']);
        $member = new Member($data);
        $member->save();
        return $member;
    }

    public function updateMember($id,$data)
    {
        if (isset($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }
        $member = Member::query()->findOrFail($id);
        $member->fill($data);
        $member->save();
        return $member;

    }

    public function removeMember($ids)
    {
        $count = Member::query()->whereIn('id',$ids)->where('supper','<>',1)->delete();
        if($count == 0 && Member::query()->whereIn('id',$ids)->count() != 0){
            throw get_error_response_exception('超级管理员不允许删除！');
        }
        return $count;
    }

    public function setRole($roles)
    {
        $this->roles()->detach();
        foreach ($roles as $role){
            $this->roles()->attach($role);
        }
    }
}
