<?php
// src/Autoloader.php

spl_autoload_register(function ($class) {
    // Dossiers où chercher les classes
    $folders = [
        __DIR__ . '/Models/',
        __DIR__ . '/Controllers/',
        __DIR__ . '/../database/' // Pour la classe Database
    ];

    foreach ($folders as $folder) {
        $file = $folder . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
