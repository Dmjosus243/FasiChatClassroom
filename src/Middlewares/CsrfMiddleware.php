<?php
namespace Middlewares;

use Core\Middleware;
use Core\Request;
use Core\Response;
use Helpers\SecurityHelper;

class CsrfMiddleware extends Middleware
{
    public function handle(Request $request, Response $response): void
    {
        if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'])) {
            $body = $request->getBody();
            $token = $body['csrf_token'] ?? '';
            if (!SecurityHelper::verifyCsrfToken($token)) {
                $response->json(['error' => 'Token CSRF invalide'], 403);
            }
        }
    }
}