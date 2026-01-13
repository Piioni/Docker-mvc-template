<?php

namespace Core;

use Core\Middleware\Authenticated;
use Core\Middleware\Guest;
use Core\Middleware\Middleware;

class Router
{
    protected $routesList = [];

    public function add($method, $uri, $controller)
    {
        $this->routesList[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
            'middleware' => null
        ];

        return $this;
    }

    public function get($uri, $controller)
    {
        return $this->add('GET', $uri, $controller);
    }

    public function post($uri, $controller)
    {
        return $this->add('POST', $uri, $controller);
    }

    public function delete($uri, $controller)
    {
        return $this->add('DELETE', $uri, $controller);
    }

    public function patch($uri, $controller)
    {
        return $this->add('PATCH', $uri, $controller);
    }

    public function put($uri, $controller)
    {
        return $this->add('PUT', $uri, $controller);
    }

    public function only($key)
    {
        $this->routesList[array_key_last($this->routesList)]['middleware'] = $key;

        return $this;
    }

    public function route($uri, $method): void
    {

        $requestedMethod = strtoupper($method);

        foreach ($this->routesList as $routeEntry) {
            if ($routeEntry['uri'] === $uri && $routeEntry['method'] === $requestedMethod) {
                Middleware::resolve($routeEntry['middleware']);

                $controller = $routeEntry['controller'];

                // If controller is in the form Controller@method, resolve as class
                if (is_string($controller) && str_contains($controller, '@')) {
                    [$controllerName, $action] = explode('@', $controller);

                    $class = 'App\\Controllers\\' . $controllerName;

                    if (class_exists($class)) {
                        $controllerInstance = new $class();
                        $controllerInstance->{$action}();
                        return;
                    }

                    abort_with_status(404);
                }
                return;
            }
        }

        // If URI exists with different method, return 405
        $foundUri = false;
        $allowedMethods = [];
        foreach ($this->routesList as $routeEntry) {
            if ($routeEntry['uri'] === $uri) {
                $foundUri = true;
                $allowedMethods[] = $routeEntry['method'];
            }
        }

        if ($foundUri) {
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: '.implode(', ', $allowedMethods));
            echo "<h1>405 Method Not Allowed</h1><p>Allowed methods: ".implode(', ', $allowedMethods)."</p>";
            exit();
        }

        abort_with_status(404);
    }

    public function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'] ?? '/';
    }

}
