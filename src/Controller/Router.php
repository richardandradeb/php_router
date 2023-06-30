<?php 

namespace Richard\PhpRouter\Controller;

use \Exception;

final class Router {
    
    private array $routes = [
        [
            'path' => '',
            'methods' => [''],
            'controller' => ''
        ]
    ];

    private string $controllerNamespace;

    public function __construct(string $controllerNamespace)
    {
        $this->controllerNamespace = $controllerNamespace;
    }

    private function getRoutes() : array
    {
        return $this->routes;
    }

    public static function setHeaders(array $headers) : void 
    {
        foreach ($headers as $key => $value) {
            header("$key: $value");
        }
    }

    public function run() : mixed 
    {
        $currentPath = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $foundRoute = '';
        $params = '';

        foreach ($this->getRoutes() as $index => $routes) {
            if($currentPath === $routes['path'] || $currentPath === $routes['path'] . '/'){
                if(!in_array($requestMethod, $routes['methods'])){
                    continue;
                }
                $foundRoute = $routes;
                break;
            }
        }
        
        if($foundRoute === ''){
            foreach ($this->getRoutes() as $index => $routes) {
                $treatedRoute = preg_replace('/({)([\w]+)(})/', '', $routes['path']);

                if($routes['path'] === $treatedRoute){
                    continue;
                }

                $treatedSlashesRoute = preg_replace('/\//', '\/', $treatedRoute);
                if(!str_contains($currentPath, $treatedRoute) && !preg_match('/^' . $treatedSlashesRoute . '([\w]+)$/', $currentPath)){
                    continue;
                }

                if(!in_array($_SERVER['REQUEST_METHOD'], $routes['methods'])){
                    return http_response_code(405);
                }

                $foundRoute = $routes;
                    
                $params = $this->getParamValues($treatedSlashesRoute, $currentPath, $routes['path']);
                break;
            }
        }

        if($foundRoute === ''){
            return http_response_code(404);
        }

        $controller = $this->controllerNamespace . "Controller\\" . $foundRoute['controller'];

        try {
            if($params === ''){
                return new $controller($params, $foundRoute['path']);
            }

            return new $controller($params, $foundRoute['path']);

        } catch (\Error $e) {
            return http_response_code(500);
        }
    }

    private function getParamValues(string $treatedSlashesRoute, string $currentPath, string $route) : array
    {
        $paramValue = preg_replace('/^' . $treatedSlashesRoute . '/', '', $currentPath);

        $paramName = preg_match_all('/({)([\w]+)(})/', $route, $matches);

        unset($matches[0]);
        
        foreach ($matches as $index => $value) {
            foreach ($value as $key => $match) {
                if($match === '{' || $match === '}')
                unset($matches[$index]);
            }
        }

        $paramName = $matches[2][0];

        return [
            $paramName => $paramValue
        ];
    }

    public function setRoute(array $route) : void
    {

        if($this->validateRoute($route)){
            
            $this->routes[] = $route;
            return;

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
        }

        return true;
    }

}
