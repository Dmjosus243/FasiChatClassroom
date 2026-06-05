<?php
// src/Controllers/LoginController.php
require_once __DIR__ . '/../Autoloader.php';
require_once __DIR__ . '/../../database/Database.php';

$dbInstance = new Database();
$db = $dbInstance->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // On utilise la méthode statique de Utilisateur pour vérifier n'importe quel rôle
    $user = Utilisateur::authentifier($db, $email, $password);

    if ($user) {
        session_start();
        $_SESSION['user'] = $user;
        
        // Redirection spécifique selon le rôle avec un switch
        switch ($user['role']) {
            case 'etudiant':
                header('Location: /FasiChatClassroom/public/dashboard_etudiant');
                break;
            case 'enseignant':
            case 'assistant':
                header('Location: /FasiChatClassroom/public/dashboard_enseignant');
                break;
            case 'doyen':
                header('Location: /FasiChatClassroom/public/dashboard_admin');
                break;
            case 'vice-doyen':
                header('Location: /FasiChatClassroom/public/dashboard_vicedoyen');
                break;
            case 'apparitaire':
                header('Location: /FasiChatClassroom/public/dashboard_apparitaire');
                break;
            default:
                header('Location: /FasiChatClassroom/public/login');
                break;
        }
        exit();
    } else {
        header('Location: /FasiChatClassroom/public/login?error=1');
        exit();
    }
} else {
    header('Location: /FasiChatClassroom/public/login');
    exit();
}
