<?php
namespace App\Services\Route;
use Illuminate\Routing\Router as BaseRouter;

class Router extends BaseRouter
{
    protected $groupName = [];
    protected function newRoute($methods, $uri, $action)
    {
        return (new Route($methods, $uri, $action))
            ->setRouter($this)
            ->setContainer($this->container);
    }

    public function getCurrentGroupName()
    {
        return  end($this->groupName);
    }

    public function group(array $attributes, $routes,$name = null)
    {
        if($name !== null) array_push($this->groupName,$name);
        parent::group($attributes,$routes);
        if($name !== null) array_pop($this->groupName);
    }


    /**
     * Dynamically handle calls into the router instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        return (new RouteRegistrar($this))->attribute($method, $parameters[0]);
    }

}