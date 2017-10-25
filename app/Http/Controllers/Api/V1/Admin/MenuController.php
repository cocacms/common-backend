<?php
namespace App\Http\Controllers\Api\V1\Admin;
use App\Http\Controllers\Controller;
use App\Http\Response\ErrorResponse;
use App\Http\Response\SuccessResponse;
use App\Models\Member;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

class MenuController extends Controller
{

    public function backend(Menu $menu)
    {
        $backend = $menu->backend();
        $backendChild = $backend->getDescendants()->toHierarchy();

        return new SuccessResponse(
            [
                'root' => $backend->id,
                'children' => array_values($backendChild->toArray())
            ]
        );
    }

    public function frontend(Menu $menu)
    {
        $frontend = $menu->frontend();
        $frontendChild = $frontend->getDescendants()->toHierarchy();
        return new SuccessResponse(
            [
                'root' => $frontend->id,
                'children' => array_values($frontendChild->toArray())
            ]
        );
    }

    public function up($id)
    {
        $menu = Menu::query()->findOrFail($id);
        $menu->moveLeft();
        return new SuccessResponse();

    }

    public function down($id)
    {
        $menu = Menu::query()->findOrFail($id);
        $menu->moveRight();
        return new SuccessResponse();

    }

    public function delete($id,Menu $menu)
    {
        $menu->findOrFail($id)->delete();
        return new SuccessResponse();
    }

    public function update($id, Menu $menu,Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $data = $request->only('bpid','icon','name','route','parent_id','tag','show');
        $menu->updateMenu($id,$data);
        return new SuccessResponse();

    }

    public function create(Menu $menu,Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $data = $request->only('bpid','icon','name','route','parent_id','tag','show');
        $menu->createMenu($data);
        return new SuccessResponse();
    }
}