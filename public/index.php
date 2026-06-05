<?php

// Récupère l'URL demandée
$request = $_SERVER['REQUEST_URI'];

$basePath = '/FasiChatClassroom/public';


$path = str_replace($basePath, '', $request);
$path = trim($path, '/');


switch ($path) {
    case '':
    case 'login':
    case 'login.php':
        require __DIR__ . '/../views/login.php';
        break;
    case 'login-handler':
        require __DIR__ . '/../src/Controllers/LoginController.php';
        break;

    case 'dashboard_etudiant':
    case 'dashboard_etudiant.php':
        require __DIR__ . '/../views/dashboard_etudiant.php';
        break;
    case 'dashboard_enseignant':
    case 'dashboard_enseignant.php':
        require __DIR__ . '/../views/dashboard_enseignant.php';
        break;
    case 'dashboard_admin':
    case 'dashboard_admin.php':
        require __DIR__ . '/../views/dashboard_admin.php';
        break;
    case 'dashboard_vicedoyen':
    case 'dashboard_vicedoyen.php':
        require __DIR__ . '/../views/dashboard_vicedoyen.php';
        break;
    case 'dashboard_apparitaire':
    case 'dashboard_apparitaire.php':
        require __DIR__ . '/../views/dashboard_apparitaire.php';
        break;
    case 'valve-publish':

        session_start();
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'apparitaire') {
            $dbInstance = new Database();
            $db = $dbInstance->getConnection();
            $apparitaire = new Apparitaire($db, $_SESSION['user']);
            
            if ($apparitaire->publierAnnonce($_POST['titre'], $_POST['contenu'])) {
                header('Location: /FasiChatClassroom/public/valve?success=1');
            } else {
                header('Location: /FasiChatClassroom/public/valve?error=1');
            }
        } else {
            header('Location: /FasiChatClassroom/public/login');
        }
        exit();
        break;
    case 'valve':

    case 'valve.php':
        require __DIR__ . '/../views/valve.php';
        break;
    default:
        require __DIR__ . '/../views/login.php';
        break;
}
