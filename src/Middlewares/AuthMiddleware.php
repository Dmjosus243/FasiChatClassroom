<?php
namespace Middlewares;

use Core\Middleware;
use Core\Request;
use Core\Response;
use Helpers\SessionHelper;

class AuthMiddleware extends Middleware
{
    public function handle(Request $request, Response $response): void
    {
        if (!SessionHelper::isLoggedIn()) {
            $response->json(['error' => 'Non authentifié'], 401);
            exit();
        }
    }
}