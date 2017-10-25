<?php
namespace App\Http\Controllers\Api\V1\Admin;
use App\Http\Controllers\Controller;
use App\Http\Response\ErrorResponse;
use App\Http\Response\SuccessResponse;
use App\Models\Member;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

/**
 * Class MemberController
 * @package App\Http\Controllers\Api\V1
 * @
 */
class MemberController extends Controller
{
    public function login(Request $request)
    {

        $data = $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = Member::where([
            'username' => $data['username']
        ])->first();

        if(is_null($user)){
            return new ErrorResponse("账号不存在！");

        }
        if (Hash::check($data['password'],$user->password)){
            $token = Auth::login($user);
            return new SuccessResponse($token);
        }else{
            return new ErrorResponse("请检查账号密码是否正确！");
        }
    }

    public function logout()
    {
        Auth::logout();
        return new SuccessResponse();
    }

    public function info($id = null)
    {
        $user = is_null($id) ? Auth::user() : Member::findOrFail($id);
        unset($user->password);
        unset($user->remember_token);
        $user->avatar =
            [
                'url' => $user->avatar,
                'uri' => asset($user->avatar)
            ];
        return new SuccessResponse($user);
    }

    public function lists(Request $request)
    {
        $pageSize = $request->input('pageSize',$this->pageSize);
        $filter = $request->input('filter','');
        $pdata = $this->handleFilter(Member::class,$filter)->with('roles')->paginate($pageSize);
        $data = $pdata->getCollection()->map(function ($item) {
            $item->avatar =
                [
                    'url' => $item->avatar,
                    'uri' => asset($item->avatar)
                ];
            $item->password = '';
            $item->remember_token = '';
            return $item;
        });

        $pdata->setCollection($data);
        return new SuccessResponse($pdata);
    }

    public function create(Request $request,Member $member)
    {

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $data = $request->only('avatar','mail','nickname','sex','tel','birthday','username','password');
        $roles = $request->input('role',[]);
        try{
            $member->addMember($data)->setRole($roles);
        }catch (QueryException $e){
            if (stripos( $e->getMessage() , 'members_username_unique')){
                return new ErrorResponse('账号已存在！');

            }
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessResponse();
    }

    public function update(Request $request,$id,Member $member)
    {
        $data = $request->only('avatar','mail','nickname','sex','tel','birthday','password');
        $roles = $request->input('role',[]);

        $member->updateMember($id,$data)->setRole($roles);

        return new SuccessResponse();
    }


    public function delete(Request $request,Member $member)
    {
        $id = $request->input('id',null);
        if(!is_null($id)){
            $ids = [$id];
        }else{
            $ids = $request->input('ids',[]);
        }

        $member->removeMember($ids);
        return new SuccessResponse();
    }
}