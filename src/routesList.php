<?php
$router->addRoute('GET', '/gallery', $galleryController, 'index');
$router->addRoute('GET', '/register', $authController, 'showRegister');
$router->addRoute('POST', '/register', $authController, 'register');
$router->addRoute('GET', '/login', $authController, 'showLogin');
$router->addRoute('GET', '/confirm', $authController, 'confirmEmail');
$router->addRoute('GET', '/expired-token', $authController, 'showExpiredToken');
