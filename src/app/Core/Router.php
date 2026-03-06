<?php

class Router {
    private array $routesTable = [];

    public function addRoute(string $uri, array $controller) {
        $this->routesTable[$uri] = $controller;
    }

    public function dispatch(string $requestUri) {
        foreach ($this->routesTable as $key => $value) {
            if ($key === $requestUri) {
                $controller = new $value[0];
                $method = $value[1];
                $controller->$method();
                return;
            }
        }
        http_response_code(404);
    }
}

