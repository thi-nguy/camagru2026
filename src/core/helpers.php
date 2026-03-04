<?php

function render(string $view, array $data = []) {
    extract($data);
    require __DIR__ . '/../app/Views/layout/header.php';
    require('../app/Views/' . $view . '.php');
    require __DIR__ . '/../app/Views/layout/footer.php';
}