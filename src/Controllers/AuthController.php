<?php
namespace Controllers;

use Core\Controller;
use Core\Request;
use Core\Response;
use Helpers\SessionHelper;
use Helpers\SecurityHelper;
use database\Database;

class AuthController extends Controller
{
    private $db;

    public function __construct()
    {
        $dbConfig = require __DIR__ . '/../../config/database.php';
        $database = new Database($dbConfig);
        $this->db = $database->getConnection();
    }

    public function showLogin(Request $request, Response $response): void
    {
        if (SessionHelper::isLoggedIn()) {
            $this->redirectToDashboard();
        }
        $this->render('login');
    }

    public function login(Request $request, Response $response): void
    {
        $data = $request->getBody();
        
        if (empty($data['email']) || empty($data['password'])) {
            $response->json(['success' => false, 'error' => 'Email et mot de passe requis'], 400);
            return;
        }
        
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE email = :email");
        $stmt->execute(['email' => $data['email']]);
        $user = $stmt->fetch();
        
        if (!$user || !SecurityHelper::verifyPassword($data['password'], $user['mot_de_passe'])) {
            $response->json(['success' => false, 'error' => 'Email ou mot de passe incorrect'], 401);
            return;
        }
        
        unset($user['mot_de_passe']);
        SessionHelper::setUser($user);
        
        $response->json([
            'success' => true,
            'role' => $user['role'],
            'redirect' => $this->getDashboardUrl($user['role'])
        ]);
    }

    public function logout(Request $request, Response $response): void
    {
        SessionHelper::destroy();
        $this->redirect('/FasiChatClassroom/public/login');
    }

    private function redirectToDashboard(): void
    {
        $role = SessionHelper::getUserRole();
        $this->redirect($this->getDashboardUrl($role));
    }

    private function getDashboardUrl(string $role): string
    {
        $dashboards = [
            'etudiant' => '/FasiChatClassroom/public/dashboard/etudiant',
            'enseignant' => '/FasiChatClassroom/public/dashboard/enseignant',
            'assistant' => '/FasiChatClassroom/public/dashboard/assistant',
            'apparitaire' => '/FasiChatClassroom/public/dashboard/apparitaire',
            'doyen' => '/FasiChatClassroom/public/dashboard/doyen',
            'vice-doyen' => '/FasiChatClassroom/public/dashboard/vicedoyen'
        ];
        return $dashboards[$role] ?? '/FasiChatClassroom/public/login';
    }
}