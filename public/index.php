<?php
require_once __DIR__ . '/../bootstrap.php';
use Core\Router;
use Core\Request;
use Core\Response;

$router = new Router(new Request(), new Response());
require_once __DIR__ . '/../routes/web.php';
require_once __DIR__ . '/../routes/api.php';
$router->dispatch();