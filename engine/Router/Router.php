<?php
declare(strict_types=1);


namespace Engine\Router;


class Router
{
    private array $routes = [];

    public function add(string $url, string $controller, string $method)
    {
        $this->routes[] = [
            '_route' => $url,
            '_controller' => $controller,
            '_action' => $method
        ];
    }

    public function match(string $url):array
    {
        foreach ($this->routes as $route){
            if($route['_route'] == $url){
                return $route;
            }
        }

        throw new \LogicException("Route not found " . $url);
    }

}
