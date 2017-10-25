<?php
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

namespace App\Services\Route;

use Illuminate\Routing\RouteRegistrar as BaseRouteRegistrar;
class RouteRegistrar extends BaseRouteRegistrar
{
    /**
     * Create a route group with shared attributes.
     *
     * @param  \Closure|string  $callback
     * @param name
     * @return void
     */
    public function group($callback,$name = null)
    {
        $this->router->group($this->attributes, $callback, $name);
    }
}