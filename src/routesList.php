<?php
$router->addRoute('GET', '/gallery', $galleryController, 'index');
$router->addRoute('GET', '/register', $authController, 'showRegister', false);
$router->addRoute('POST', '/register', $authController, 'register', false);
$router->addRoute('GET', '/login', $authController, 'showLogin', false);
$router->addRoute('POST', '/login', $authController, 'handleLogin', false);
$router->addRoute('POST', '/logout', $authController, 'handleLogout');
$router->addRoute('GET', '/confirm', $authController, 'confirmEmail', false);
$router->addRoute('GET', '/expired_token', $authController, 'showExpiredToken', false);
$router->addRoute('POST', '/expired_token', $authController, 'resendToken', false);
$router->addRoute('GET', '/password_reset', $authController, 'showResetPassword', false);
