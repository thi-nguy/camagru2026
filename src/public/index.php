<?php 

require __DIR__ . '/../core/helpers.php';

spl_autoload_register(function($className) {
    $folders = ['/../core/', '/../app/Controllers/'];
    foreach ($folders as $folder) {
        $file = __DIR__ . $folder . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

$router = new Router();
require_once __DIR__ . '/../routesList.php';
$router->dispatch($_SERVER['REQUEST_URI']);
