<?php

class Router
{
    private array $routesTable = [];

    public function addRoute(string $method, string $uri, object $controller, string $action): void
    {
        $key = $method . ':' . $uri;
        $this->routesTable[$key] = [$controller, $action];
    }

    public function dispatch(string $requestUri): void
    {
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routesTable as $key => [$controller, $action]) {
            [$routeMethod, $routeUri] = explode(':', $key, 2);

            $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routeUri);

            if ($routeMethod === $method && preg_match("#^$pattern$#", $requestUri, $matches)) {
                array_shift($matches);
                $controller->$action(...$matches);
                return;
            }
        }

        http_response_code(404);
        require __DIR__ . '/../Views/404.php';
    }
}

