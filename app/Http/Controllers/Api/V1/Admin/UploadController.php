<?php
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

namespace App\Http\Controllers\Api\V1\Admin;


use App\Http\Controllers\Controller;
use App\Http\Response\ErrorResponse;
use App\Http\Response\SuccessResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $name = $request->input('name','file');
        $file = $request->file($name,null);
        try{
            if(is_null($file)){
                $result = new ErrorResponse('没有获取到上传的文件！');
            }else{
                $path = $file->store('uploads/'.date('Ymd',time()),'public');
                $url = Storage::url($path);
                $uri = asset($url);
                $result = new SuccessResponse([
                    'url' => $url,
                    'uri' => $uri
                ]);
            }
        }catch (\Exception $e){
            $result = new ErrorResponse($e->getMessage());
        }

        return $result;
    }

}