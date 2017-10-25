<?php
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

namespace App\Http\Response;


class ErrorResponse extends ResponseTemplate
{
    public function __construct($message = 'error',$code = -1)
    {
        parent::__construct($code, null, $message);
    }
}