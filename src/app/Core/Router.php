<?php

class Router
{
    private array $routesTable = [];

    public function addRoute(string $method, string $uri, object $controller, string $action, bool $isProtected = true): void
    {
        $key = $method . ':' . $uri;
        $this->routesTable[$key] = [$controller, $action, $isProtected];
    }

    public function dispatch(string $requestUri): void
    {
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routesTable as $key => [$controller, $action, $isProtected]) {
            [$routeMethod, $routeUri] = explode(':', $key, 2);

            $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routeUri);

            if ($routeMethod === $method && preg_match("#^$pattern$#", $requestUri, $matches)) {
                if ($isProtected) {
                    if (!isset($_SESSION['id'])) {
                        $_SESSION['requestUri'] = $requestUri;
                        redirect('/login');
                    }
                }
                array_shift($matches);
                $controller->$action(...$matches);
                return;
            }
        }

        http_response_code(404);
        require __DIR__ . '/../Views/404.php';
    }
}
