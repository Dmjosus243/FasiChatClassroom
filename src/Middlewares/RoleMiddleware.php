<?php
namespace Middlewares;

use Core\Middleware;
use Core\Request;
use Core\Response;
use Helpers\SessionHelper;

class RoleMiddleware extends Middleware
{
    private array $allowedRoles;

    public function __construct(array $allowedRoles)
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function handle(Request $request, Response $response): void
    {
        $userRole = SessionHelper::getUserRole();
        if (!in_array($userRole, $this->allowedRoles)) {
            $response->json(['error' => 'Accès non autorisé'], 403);
            exit();
        }
    }
}