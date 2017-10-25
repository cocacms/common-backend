<?php
/**
 * Coca-Admin is a general modular web framework developed based on Laravel 5.4 .
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 * Git:        https://github.com/rojer95/CocaAdmin
 * QQ Group:   647229346
 */

namespace App\Exceptions;

use Exception;

class UndefinedRouteException extends Exception
{
    public function __construct($name)
    {
        parent::__construct("路由 $name 未定义！");
    }
}