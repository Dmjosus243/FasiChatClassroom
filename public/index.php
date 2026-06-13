<?php
// Bootstrap de l'application
require_once __DIR__ . '/../bootstrap.php';

use Core\Router;
use Core\Request;
use Core\Response;

// Création du routeur
$request = new Request();
$response = new Response();
$router = new Router($request, $response);

// Chargement des routes
require_once __DIR__ . '/../routes/web.php';
require_once __DIR__ . '/../routes/api.php';

// Dispatch de la requête
$router->dispatch();