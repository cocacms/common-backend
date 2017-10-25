<?php
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

namespace App\Http\Response;


class SuccessResponse extends ResponseTemplate
{
    public function __construct($data = '',$message = 'success')
    {
        parent::__construct(0,$data,$message);
    }
}