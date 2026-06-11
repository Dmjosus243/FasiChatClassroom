<?php

// Récupère l'URL demandée
$request = $_SERVER['REQUEST_URI'];

$basePath = '/FasiChatClassroom/public';


// Nettoie l'URL pour n'avoir que le chemin relatif
$path = parse_url($request, PHP_URL_PATH);
$path = str_replace($basePath, '', $path);
$path = trim($path, '/');


// Helper pour une réponse JSON propre
function sendJsonResponse($data, $httpCode = 200) {
    ob_clean();
    header('Content-Type: application/json');
    http_response_code($httpCode);
    echo json_encode($data);
    exit();
}

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
    case 'file-upload':
        session_start();
        if (!isset($_SESSION['user'])) {
            sendJsonResponse(['error' => 'Non connecté'], 403);
        }
        require_once __DIR__ . '/../src/Autoloader.php';
        require_once __DIR__ . '/../database/Database.php';
        $db = (new Database())->getConnection();
        $fileModel = new Fichier($db);
        if (isset($_FILES['file'])) {
            $fileId = $fileModel->upload($_FILES['file']);
            sendJsonResponse(['success' => (bool)$fileId, 'file_id' => $fileId]);
        } else {
            sendJsonResponse(['error' => 'Aucun fichier reçu'], 400);
        }
        break;
    case 'message-send':
        session_start();
        if (!isset($_SESSION['user'])) {
            sendJsonResponse(['error' => 'Non connecté'], 403);
        }
        require_once __DIR__ . '/../src/Autoloader.php';
        require_once __DIR__ . '/../database/Database.php';
        $db = (new Database())->getConnection();
        $msgModel = new Message($db);
        try {
            $success = $msgModel->envoyer(
                $_SESSION['user']['id'],
                $_POST['contenu'] ?? '',
                $_POST['type'] ?? 'prive',
                !empty($_POST['destinataire_id']) ? intval($_POST['destinataire_id']) : null,
                !empty($_POST['cours_id']) ? intval($_POST['cours_id']) : null,
                !empty($_POST['promotion_id']) ? intval($_POST['promotion_id']) : null,
                !empty($_POST['fichier_id']) ? intval($_POST['fichier_id']) : null
            );
            sendJsonResponse(['success' => $success]);
        } catch (Exception $e) {
            sendJsonResponse(['success' => false, 'error' => $e->getMessage()], 400);
        }
        break;
    case 'message-poll':
        session_start();
        if (!isset($_SESSION['user'])) {
            sendJsonResponse(['error' => 'Non connecté'], 403);
        }
        require_once __DIR__ . '/../src/Autoloader.php';
        require_once __DIR__ . '/../database/Database.php';
        $db = (new Database())->getConnection();
        $msgModel = new Message($db);
        $type = $_GET['type'] ?? 'prive';
        $id = !empty($_GET['id']) ? intval($_GET['id']) : 0;
        
        $messages = ($type === 'prive') ? $msgModel->recupererPrives($_SESSION['user']['id'], $id) :
                    (($type === 'public') ? $msgModel->recupererPublics($id) : $msgModel->recupererMur($id));
        
        sendJsonResponse($messages);
        break;
    case 'convocation-send':
        session_start();
        if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['doyen', 'vice-doyen'])) {
            require_once __DIR__ . '/../src/Autoloader.php';
            require_once __DIR__ . '/../database/Database.php';
            $db = (new Database())->getConnection();
            $admin = ($_SESSION['user']['role'] === 'doyen') ? new Doyen($db, $_SESSION['user']) : new ViceDoyen($db, $_SESSION['user']);
            
            if ($admin->convoquer($db, $_POST['objet'], $_POST['date'], $_POST['heure'], $_POST['lieu'], $_POST['message'] ?? '')) {
                sendJsonResponse(['success' => true]);
            } else {
                sendJsonResponse(['success' => false, 'error' => 'Erreur'], 500);
            }
        } else {
            sendJsonResponse(['error' => 'Accès refusé'], 403);
        }
        break;
    default:
        require __DIR__ . '/../views/login.php';
        break;
}

