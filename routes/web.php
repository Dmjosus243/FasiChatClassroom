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

// Routes protégées
$protected = [AuthMiddleware::class, CsrfMiddleware::class];

// Dashboards
$router->get('/dashboard/etudiant', [DashboardController::class, 'etudiant'], $protected);
$router->get('/dashboard/enseignant', [DashboardController::class, 'enseignant'], $protected);
$router->get('/dashboard/assistant', [DashboardController::class, 'assistant'], $protected);
$router->get('/dashboard/apparitaire', [DashboardController::class, 'apparitaire'], $protected);
$router->get('/dashboard/doyen', [DashboardController::class, 'doyen'], $protected);
$router->get('/dashboard/vicedoyen', [DashboardController::class, 'vicedoyen'], $protected);

// Messages
$router->post('/message/send', [MessageController::class, 'send'], $protected);
$router->post('/message/audio', [MessageController::class, 'sendAudio'], $protected);
$router->post('/message/file', [MessageController::class, 'sendFile'], $protected);
$router->get('/message/poll', [MessageController::class, 'poll'], $protected);

// Convocations (Doyen & Vice-doyen)
$convocationRoles = [AuthMiddleware::class, CsrfMiddleware::class, function() {
    return new RoleMiddleware(['doyen', 'vice-doyen']);
}];
$router->post('/convocation/send', [ConvocationController::class, 'send'], $convocationRoles);

// Valve (Apparitaire)
$valveRoles = [AuthMiddleware::class, CsrfMiddleware::class, function() {
    return new RoleMiddleware(['apparitaire']);
}];
$router->get('/valve', [ValveController::class, 'show'], $protected);
$router->post('/valve/publish', [ValveController::class, 'publish'], $valveRoles);
$router->put('/valve/{id}', [ValveController::class, 'update'], $valveRoles);
$router->delete('/valve/{id}', [ValveController::class, 'delete'], $valveRoles);

// Étudiant
$etudiantRoles = [AuthMiddleware::class, function() {
    return new RoleMiddleware(['etudiant']);
}];
$router->get('/etudiant/promotion', [EtudiantController::class, 'getPromotion'], $etudiantRoles);
$router->get('/etudiant/cours', [EtudiantController::class, 'getCours'], $etudiantRoles);

// Enseignant
$enseignantRoles = [AuthMiddleware::class, function() {
    return new RoleMiddleware(['enseignant']);
}];
$router->get('/enseignant/cours', [EnseignantController::class, 'getCours'], $enseignantRoles);
$router->get('/enseignant/etudiants/{cours_id}', [EnseignantController::class, 'getEtudiants'], $enseignantRoles);

// Assistant
$assistantRoles = [AuthMiddleware::class, function() {
    return new RoleMiddleware(['assistant']);
}];
$router->get('/assistant/cours', [AssistantController::class, 'getCours'], $assistantRoles);
$router->get('/assistant/etudiants/{cours_id}', [AssistantController::class, 'getEtudiants'], $assistantRoles);
$router->post('/assistant/question', [AssistantController::class, 'questionMur'], $assistantRoles);

// Apparitaire
$router->get('/apparitaire/annonces', [ApparitaireController::class, 'getAnnonces'], $valveRoles);
$router->post('/apparitaire/annonce', [ApparitaireController::class, 'createAnnonce'], $valveRoles);
$router->put('/apparitaire/annonce/{id}', [ApparitaireController::class, 'updateAnnonce'], $valveRoles);
$router->delete('/apparitaire/annonce/{id}', [ApparitaireController::class, 'deleteAnnonce'], $valveRoles);

// Doyen
$doyenRoles = [AuthMiddleware::class, function() {
    return new RoleMiddleware(['doyen']);
}];
$router->get('/doyen/statistiques', [DoyenController::class, 'getStatistiques'], $doyenRoles);
$router->get('/doyen/enseignants', [DoyenController::class, 'getEnseignants'], $doyenRoles);
$router->get('/doyen/assistants', [DoyenController::class, 'getAssistants'], $doyenRoles);

// Vice-doyen
$vicedoyenRoles = [AuthMiddleware::class, function() {
    return new RoleMiddleware(['vice-doyen']);
}];
$router->get('/vicedoyen/statistiques', [VicedoyenController::class, 'getStatistiques'], $vicedoyenRoles);
$router->get('/vicedoyen/convocations', [VicedoyenController::class, 'getConvocations'], $vicedoyenRoles);