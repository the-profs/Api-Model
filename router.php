<?php
class Router {
    private $routes = [];

    public function __construct() {
        $this->addRouteFromClasses();
    }

    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch($method, $path) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($this->patternToRegex($route['path']), $path, $matches)) {
                $handler = explode('@', $route['handler']);
                $controllerName = $handler[0];
                $methodName = $handler[1];

                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    return $controller->$methodName(...$matches);
                } else {
                    return $this->notFoundResponse();
                }
            }
        }

        return $this->notFoundResponse();
    }

    private function patternToRegex($pattern) {
        return '#^' . str_replace(['/', '{id}'], ['\/', '(\d+)'], $pattern) . '$#';
    }

    private function notFoundResponse() {
        http_response_code(404);
        return json_encode(['error' => 'Route not found']);
    }
    private function addRouteFromClasses() {
        $classNames = glob('classes/*.php');
    
        foreach ($classNames as $className) {
            require_once $className;
            $class = basename($className, '.php');
    
            // Verifica se la classe è un controller
            if (strpos($class, 'Controller') !== false) {
                $controllerInstance = new $class();
                $reflectionClass = new ReflectionClass($controllerInstance);
                
                // Ottieni tutti i metodi pubblici della classe
                $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);
    
                foreach ($methods as $method) {
                    // Verifica se il metodo appartiene alla classe del controller
                    if ($method->class === $class) {
                        // Ottieni il nome del metodo
                        $methodName = $method->name;
                        // Genera il percorso della rotta basato sul nome del controller e del metodo
                        $route = strtolower(str_replace('Controller', '', $class)) . '/' . $methodName;
                        // Aggiungi la rotta
                        $handler = $class . '@' . $methodName;
                        $this->addRoute('GET', '/api/' . $route, $handler);
                        $this->addRoute('POST', '/api/' . $route, $handler);
                        $this->addRoute('PUT', '/api/' . $route, $handler);
                        $this->addRoute('DELETE', '/api/' . $route, $handler);
                    }
                }
            }
        }
    }
    
    
    
}
