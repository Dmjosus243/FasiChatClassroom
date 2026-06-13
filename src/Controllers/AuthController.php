<?php
namespace Controllers;

use Core\Controller;
use Core\Request;
use Core\Response;
use Helpers\SessionHelper;
use Helpers\SecurityHelper;
use Services\ValidationService;
use database\Database;

class AuthController extends Controller
{
    private $db;
    private $validator;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->validator = new ValidationService();
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
        
        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6']
        ];
        
        if (!$this->validator->validate($data, $rules)) {
            $response->json(['success' => false, 'errors' => $this->validator->getErrors()], 400);
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
        $url = $this->getDashboardUrl($role);
        $this->redirect($url);
    }

    private function getDashboardUrl(string $role): string
    {
        $dashboards = [
            ROLE_ETUDIANT => '/FasiChatClassroom/public/dashboard/etudiant',
            ROLE_ENSEIGNANT => '/FasiChatClassroom/public/dashboard/enseignant',
            ROLE_ASSISTANT => '/FasiChatClassroom/public/dashboard/assistant',
            ROLE_APPARITAIRE => '/FasiChatClassroom/public/dashboard/apparitaire',
            ROLE_DOYEN => '/FasiChatClassroom/public/dashboard/doyen',
            ROLE_VICE_DOYEN => '/FasiChatClassroom/public/dashboard/vicedoyen'
        ];
        
        return $dashboards[$role] ?? '/FasiChatClassroom/public/login';
    }
}