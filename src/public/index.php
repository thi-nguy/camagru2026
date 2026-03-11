<?php
session_start();

require __DIR__ . '/../app/Core/helpers.php';

spl_autoload_register(function($className) {
    $folders = ['/../app/Core/', '/../app/Controllers/'];
    foreach ($folders as $folder) {
        $file = __DIR__ . $folder . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

loadEnv(__DIR__ . '/../.env');

$router = new Router();
require_once __DIR__ . '/../routesList.php';
$router->dispatch($_SERVER['REQUEST_URI']);
