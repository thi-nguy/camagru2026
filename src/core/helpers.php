<?php

function render(string $view, array $data = []) {
    extract($data);
    require __DIR__ . '/../app/Views/layout/header.php';
    require('../app/Views/' . $view . '.php');
    require __DIR__ . '/../app/Views/layout/footer.php';
}

function loadEnv(string $path) {
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (!str_starts_with($line, '#')) {
            [$key, $value] = explode('=', $line, 2);
            $_ENV[$key] = $value;
        }
    }
}