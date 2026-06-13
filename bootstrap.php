<?php
// Configuration du fuseau horaire
date_default_timezone_set('Africa/Kinshasa');

// Gestion des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Démarrer la session
session_start();

// Chargement de l'autoloader
require_once __DIR__ . '/src/Autoloader.php';
Autoloader::register();

// Chargement des constantes
require_once __DIR__ . '/config/constants.php';

// Helper functions
function dd(...$vars): void {
    foreach ($vars as $var) {
        var_dump($var);
    }
    die();
}

function dump(...$vars): void {
    foreach ($vars as $var) {
        var_dump($var);
    }
}