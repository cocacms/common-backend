<?php
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

namespace App\Http\Controllers\Api\V1\Admin;


use App\Http\Controllers\Controller;
use App\Http\Response\SuccessResponse;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    public function lists()
    {
        return new SuccessResponse(Role::all());
    }

    public function create(Request $request,Role $role)
    {
        $data = $this->validate($request, [
            'name' => 'required'
        ]);

        $role->addRole($data['name']);
        return new SuccessResponse();
    }

    public function update(Request $request, $id,Role $role)
    {
        $data = $this->validate($request, [
            'name' => 'required'
        ]);

        $role->updateRole($id,$data['name']);
        return new SuccessResponse();
    }

    public function delete($id,Role $role)
    {
        $role->removeRole($id);
        return new SuccessResponse();
    }
}