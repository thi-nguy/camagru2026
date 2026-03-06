<?php

class Router {
    private array $routesTable = [];

    public function addRoute(string $method, string $uri, array $controller) {
        $key = $method . ':' . $uri;
        $this->routesTable[$key] = $controller;
    }

    public function dispatch(string $requestUri) {
        $keyAndMethod = $_SERVER['REQUEST_METHOD'] . ':' . $requestUri;
        foreach ($this->routesTable as $key => $value) {
            if ($key === $keyAndMethod) {
                $controller = new $value[0];
                $method = $value[1];
                $controller->$method();
                return;
            }
        }
        http_response_code(404);
    }
}

