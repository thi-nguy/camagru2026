<?php
$router->addRoute('GET', '/gallery', ['GalleryController', 'index']);
$router->addRoute('GET', '/register', ['AuthController', 'showRegister']);
$router->addRoute('POST', '/register', ['AuthController', 'register']);
$router->addRoute('GET', '/confirm', ['AuthController', 'confirmEmail']);
