<?php
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

namespace App\Http\Controllers\Api\V1;


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
        if(is_null($file)){
            return new ErrorResponse('没有获取到上传的文件！');
        }
        $drive = Storage::disk('qiniu');
        $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $fileName = 'uploads/'.date('Ym',time()).'/'.md5_file($file->getRealPath()).'.'.$extension;
        if(!$drive->has($fileName)){
            $result = $drive->write($fileName, file_get_contents($file->getRealPath()) );
        }else{
            $result = true;
        }

        if($result){
            return new SuccessResponse($drive->url($fileName));
        }else{
            return new ErrorResponse($result);
        }

    }

}