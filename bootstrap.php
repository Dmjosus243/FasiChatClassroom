<?php
date_default_timezone_set('Africa/Kinshasa');
error_reporting(E_ALL);
session_start();

// Charger .env
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
            putenv(trim($key) . '=' . trim($value));
        }
    }
}

// display_errors seulement en dev
if (($_ENV['APP_ENV'] ?? 'development') === 'development') {
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
}

require_once __DIR__ . '/src/Autoloader.php';
Autoloader::register();
require_once __DIR__ . '/config/constants.php';