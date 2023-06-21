<?php 

namespace Richard\PhpRouter;

use \Exception;

final class Router {
    /*
        [
        'path' => '/example',
        'methods' => 
            [
                'GET','POST'
            ]
        ]
    */
    private array $routes = [
        [
            'path' => '',
            'methods' => [''],
            'controller' => ''
        ]
    ];

    public function getRoutes() : array
    {
        unset($this->routes[0]);
        return $this->routes;
    }

    public function setRoute(array $route) : void
    {

        if($this->validateRoute($route)){
            
            $this->routes[] = $route;
            return;

            //$dynamicRoute = preg_match_all('/({)([\w]+)(})/',$route['path'],$matches);
            
            //unset($matches[0]);

            /*foreach ($matches as $index => $value) {
                foreach ($value as $key => $match) {
                    if($match === '{' || $match === '}')
                    unset($matches[$index]);
                }
            }

            $values = $this->getParamValues($matches, $route['path']);

            $this->routes[] = [
                $route,
                'params' => $values
                ];
            */
        }

        throw new Exception("Invalid route given! Check your paths and methods!");
    }

    private function validateRoute(array $route) : bool 
    {
        
        if(!array_key_exists('path',$route) || !array_key_exists('methods',$route) || !array_key_exists('controller',$route)){
            return false;
        }

        foreach ($route['methods'] as $value) {
            if(!in_array($value, ['GET','POST','PUT','PATCH','DELETE','OPTIONS','HEAD'])){
                return false;
            }
        }

        if(!preg_match('/^\/[\w\/\?\=\&{}]*/',$route['path'])){
            return false;
        }

        foreach ($this->routes as $settledRoute => $params) {
            if(in_array($route['path'], $params)){
                foreach ($route['methods'] as $key => $value) {
                    if(in_array($value, $params['methods'])){
                        throw new Exception("Route path given already exists within the given HTTP method!");
                    }
                }
            }

            if($route['controller'] === $params['controller']){
                throw new Exception("Controller is already associated within one of the routes!");
            }
        }

        return true;
    }

    public function setHeaders(array $headers) : void 
    {
        foreach ($headers as $key => $value) {
            header("$key: $value");
        }
    }

    private function setParamValues(array $matches, string $routePath) : array
    {
        $paramName = preg_match('/{([\w]+)}/', $routePath, $matches);
        unset($matches[0]);
        
        $noParamRoute = preg_replace('/{([\w]+)}/', '', $routePath);
        $treatedRoute = preg_replace('/\//', '\/', $noParamRoute);

        $pattern = '/^' . $treatedRoute . '[\w]+$/';

        if(str_contains($_SERVER['REQUEST_URI'],$noParamRoute) && preg_match($pattern, $_SERVER['REQUEST_URI'])){
            $value = preg_replace('/' . $treatedRoute . '/', '' , $_SERVER['REQUEST_URI']);
            
            return [
                $matches[1] => $value
            ];
        }

        throw new Exception("Error while solving dynamic params, check the given path!");
    }

}