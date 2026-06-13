<?php
use Core\Router;
use Middlewares\AuthMiddleware;
use Controllers\MessageController;
use Controllers\ConvocationController;

/** @var Router $router */

// API Routes (version 1)
$router->get('/api/v1/messages', [MessageController::class, 'poll'], [AuthMiddleware::class]);
$router->post('/api/v1/messages', [MessageController::class, 'send'], [AuthMiddleware::class]);
$router->post('/api/v1/convocations', [ConvocationController::class, 'send'], [AuthMiddleware::class]);