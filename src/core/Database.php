<?php

class Database {
    static $pdo_instance;

    private function __construct () {
        self::$pdo_instance = new PDO(
            "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS']
        );
        self::$pdo_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$pdo_instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    static function getInstance() {
        if (self::$pdo_instance === NULL) {
            new Database();
            return self::$pdo_instance;
        } else {
            return self::$pdo_instance;
        }
    }
}