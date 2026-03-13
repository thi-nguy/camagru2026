<?php
session_start();

require __DIR__ . '/../app/Core/helpers.php';

spl_autoload_register(function (string $className): void {
    $folders = [
        __DIR__ . '/../app/Core/',
        __DIR__ . '/../app/Controllers/',
        __DIR__ . '/../app/Models/',
    ];
    foreach ($folders as $folder) {
        $file = $folder . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

loadEnv(__DIR__ . '/../.env');

$db = Database::getInstance();
$userModel      = new UserModel($db);
$authController = new AuthController($userModel);
$galleryController = new GalleryController(); 

$router = new Router();
require_once __DIR__ . '/../routesList.php';
$uriParts = parse_url($_SERVER['REQUEST_URI']);
$router->dispatch($uriParts['path']);
