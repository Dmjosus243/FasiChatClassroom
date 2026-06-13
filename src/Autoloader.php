<?php

class Autoloader
{
    public static function register(): void
    {
        spl_autoload_register(function ($class) {
            $prefixes = [
                'Core\\' => __DIR__ . '/Core/',
                'Controllers\\' => __DIR__ . '/Controllers/',
                'Models\\' => __DIR__ . '/Models/',
                'Middlewares\\' => __DIR__ . '/Middlewares/',
                'Helpers\\' => __DIR__ . '/Helpers/',
                'Services\\' => __DIR__ . '/Services/',
                'database\\' => __DIR__ . '/../database/',
            ];

            foreach ($prefixes as $prefix => $baseDir) {
                if (strncmp($prefix, $class, strlen($prefix)) === 0) {
                    $relativeClass = substr($class, strlen($prefix));
                    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
                    if (file_exists($file)) {
                        require_once $file;
                        return;
                    }
                }
            }
        });
    }
}
