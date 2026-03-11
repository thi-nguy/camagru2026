<?php

function render(string $view, array $data = []) {
    extract($data);

    $extraCssFile = '/css/' . pathinfo($view, PATHINFO_FILENAME) . '.css';
    $haveExtraCss = file_exists(__DIR__ . '/../../public' . $extraCssFile) ? $extraCssFile : null;

    $extraJsFile = '/js/' . pathinfo($view, PATHINFO_FILENAME) . '.js';
    $haveExtraJs = file_exists(__DIR__ . '/../../public' . $extraJsFile) ? $extraJsFile : null;

    require __DIR__ . '/../Views/layout/header.php';
    require('../app/Views/' . $view . '.php');
    require __DIR__ . '/../Views/layout/footer.php';
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

function flashMessage(string $key): ?string {
    if(!isset($_SESSION[$key])) return null;
    $msg = htmlspecialchars($_SESSION[$key]);
    unset($_SESSION[$key]);
    return $msg;
}