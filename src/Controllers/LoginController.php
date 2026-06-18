<?php

require_once __DIR__ . '/../Autoloader.php';
Autoloader::register();
require_once __DIR__ . '/../../database/Database.php';

session_start();

$dbInstance = new Database();
$db = $dbInstance->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        unset($user['mot_de_passe']);
        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];

        $dashboards = [
            'etudiant' => '/FasiChatClassroom/public/dashboard/etudiant',
            'enseignant' => '/FasiChatClassroom/public/dashboard/enseignant',
            'assistant' => '/FasiChatClassroom/public/dashboard/assistant',
            'apparitaire' => '/FasiChatClassroom/public/dashboard/apparitaire',
            'doyen' => '/FasiChatClassroom/public/dashboard/doyen',
            'vice-doyen' => '/FasiChatClassroom/public/dashboard/vicedoyen'
        ];

        header('Location: ' . ($dashboards[$user['role']] ?? '/FasiChatClassroom/public/login'));
        exit();
    } else {
        header('Location: /FasiChatClassroom/public/login?error=1');
        exit();
    }
} else {
    header('Location: /FasiChatClassroom/public/login');
    exit();
}
