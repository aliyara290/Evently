<?php 
namespace App\Core;
use App\Core\Controller;

class Router {
    private array $routes = [];
    public function add(string $method, string $path, string $controller, string $action, $middleware = null, array|string $role = []) {
        $this->routes[strtoupper($method)][$path] = [
            "controller" => $controller,
            "method" => $action,
            "middleware" => $middleware,
            "role" => $role
        ];
    }

    public function get(string $path, string $controller, string $action, $middleware = null, array|string $role = []) {
        $this->add("GET", $path, $controller, $action, $middleware, $role);
    }
    public function post(string $path, string $controller, string $action, $middleware = null, array|string $role = []) {
        $this->add("POST", $path, $controller, $action, $middleware, $role);
    }
    public function delete(string $path, string $controller, string $action, $middleware = null, array|string $role = []) {
        $this->add("DELETE", $path, $controller, $action, $middleware, $role);
    }
    public function put(string $path, string $controller, string $action, $middleware = null, array|string $role = []) {
        $this->add("PUT", $path, $controller, $action, $middleware, $role);
    }
    public function patch(string $path, string $controller, string $action, $middleware = null, array|string $role = []) {
        $this->add("PATCH", $path, $controller, $action, $middleware, $role);
    }

    public function dispatch() {
        $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $method = $_SERVER["REQUEST_METHOD"];
        if(array_key_exists($uri, $this->routes[$method])) {
            $route = $this->routes[$method][$uri];
            $controller = $route["controller"];
            $action = $route["method"];
            $controller = new $controller();
            $controller->$action();
            if($route["middleware"]) {
                $middleware = $route["middleware"];
                $role = $route["role"];
                $middleware = new $middleware();
                if(!$middleware->handle($role)) {
                    header("location: /forbidden"); 
                }
            }
        } else {
            Controller::redirect("/404");
        }
    }
}