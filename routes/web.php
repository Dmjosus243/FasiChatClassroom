<?php
use Core\Router;
use Middlewares\AuthMiddleware;
use Middlewares\RoleMiddleware;
use Middlewares\CsrfMiddleware;
use Controllers\AuthController;
use Controllers\DashboardController;
use Controllers\MessageController;
use Controllers\ConvocationController;
use Controllers\ValveController;
use Controllers\EtudiantController;
use Controllers\EnseignantController;
use Controllers\AssistantController;
use Controllers\ApparitaireController;
use Controllers\DoyenController;
use Controllers\VicedoyenController;

/** @var Router $router */

// Routes publiques
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Routes protégées (authentification requise)
$protectedMiddlewares = [AuthMiddleware::class, CsrfMiddleware::class];

// Dashboard routes
$router->get('/dashboard/etudiant', [DashboardController::class, 'etudiant'], $protectedMiddlewares);
$router->get('/dashboard/enseignant', [DashboardController::class, 'enseignant'], $protectedMiddlewares);
$router->get('/dashboard/assistant', [DashboardController::class, 'assistant'], $protectedMiddlewares);
$router->get('/dashboard/apparitaire', [DashboardController::class, 'apparitaire'], $protectedMiddlewares);
$router->get('/dashboard/doyen', [DashboardController::class, 'doyen'], $protectedMiddlewares);
$router->get('/dashboard/vicedoyen', [DashboardController::class, 'vicedoyen'], $protectedMiddlewares);

// Routes messages
$router->post('/message/send', [MessageController::class, 'send'], $protectedMiddlewares);
$router->get('/message/poll', [MessageController::class, 'poll'], $protectedMiddlewares);

// Routes convocations (Doyen et Vice-doyen uniquement)
$convocationMiddlewares = [AuthMiddleware::class, CsrfMiddleware::class, function() {
    return new RoleMiddleware([ROLE_DOYEN, ROLE_VICE_DOYEN]);
}];
$router->post('/convocation/send', [ConvocationController::class, 'send'], $convocationMiddlewares);

// Routes Valve (Apparitaire uniquement)
$valveMiddlewares = [AuthMiddleware::class, CsrfMiddleware::class, function() {
    return new RoleMiddleware([ROLE_APPARITAIRE]);
}];
$router->get('/valve', [ValveController::class, 'show'], $protectedMiddlewares);
$router->post('/valve/publish', [ValveController::class, 'publish'], $valveMiddlewares);
$router->put('/valve/{id}', [ValveController::class, 'update'], $valveMiddlewares);
$router->delete('/valve/{id}', [ValveController::class, 'delete'], $valveMiddlewares);

// Routes Étudiant
$etudiantMiddlewares = [AuthMiddleware::class, function() {
    return new RoleMiddleware([ROLE_ETUDIANT]);
}];
$router->get('/etudiant/promotion', [EtudiantController::class, 'getPromotion'], $etudiantMiddlewares);
$router->get('/etudiant/cours', [EtudiantController::class, 'getCours'], $etudiantMiddlewares);

// Routes Enseignant
$enseignantMiddlewares = [AuthMiddleware::class, function() {
    return new RoleMiddleware([ROLE_ENSEIGNANT]);
}];
$router->get('/enseignant/cours', [EnseignantController::class, 'getCours'], $enseignantMiddlewares);
$router->get('/enseignant/etudiants/{cours_id}', [EnseignantController::class, 'getEtudiants'], $enseignantMiddlewares);

// Routes Assistant
$assistantMiddlewares = [AuthMiddleware::class, function() {
    return new RoleMiddleware([ROLE_ASSISTANT]);
}];
$router->get('/assistant/cours', [AssistantController::class, 'getCours'], $assistantMiddlewares);
$router->get('/assistant/etudiants/{cours_id}', [AssistantController::class, 'getEtudiants'], $assistantMiddlewares);
$router->post('/assistant/question', [AssistantController::class, 'questionMur'], $assistantMiddlewares);

// Routes Apparitaire
$router->get('/apparitaire/annonces', [ApparitaireController::class, 'getAnnonces'], $valveMiddlewares);
$router->post('/apparitaire/annonce', [ApparitaireController::class, 'createAnnonce'], $valveMiddlewares);
$router->put('/apparitaire/annonce/{id}', [ApparitaireController::class, 'updateAnnonce'], $valveMiddlewares);
$router->delete('/apparitaire/annonce/{id}', [ApparitaireController::class, 'deleteAnnonce'], $valveMiddlewares);

// Routes Doyen
$doyenMiddlewares = [AuthMiddleware::class, function() {
    return new RoleMiddleware([ROLE_DOYEN]);
}];
$router->get('/doyen/statistiques', [DoyenController::class, 'getStatistiques'], $doyenMiddlewares);
$router->get('/doyen/enseignants', [DoyenController::class, 'getEnseignants'], $doyenMiddlewares);
$router->get('/doyen/assistants', [DoyenController::class, 'getAssistants'], $doyenMiddlewares);

// Routes Vice-doyen
$vicedoyenMiddlewares = [AuthMiddleware::class, function() {
    return new RoleMiddleware([ROLE_VICE_DOYEN]);
}];
$router->get('/vicedoyen/statistiques', [VicedoyenController::class, 'getStatistiques'], $vicedoyenMiddlewares);
$router->get('/vicedoyen/convocations', [VicedoyenController::class, 'getConvocations'], $vicedoyenMiddlewares);