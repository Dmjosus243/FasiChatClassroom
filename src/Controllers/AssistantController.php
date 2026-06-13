<?php
namespace Controllers;

use Core\Controller;
use Core\Request;
use Core\Response;
use Helpers\SessionHelper;
use database\Database;

class AssistantController extends Controller
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getCours(Request $request, Response $response): void
    {
        $userId = SessionHelper::getUserId();
        
        $stmt = $this->db->prepare("
            SELECT c.* FROM cours c
            WHERE c.assistant_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        $cours = $stmt->fetchAll();
        
        $response->json($cours);
    }

    public function getEtudiants(Request $request, Response $response): void
    {
        $coursId = $request->getParam('cours_id');
        
        $stmt = $this->db->prepare("
            SELECT u.id, u.nom, u.prenom, u.email, e.matricule 
            FROM utilisateurs u
            JOIN etudiants e ON e.user_id = u.id
            JOIN inscription_cours ic ON ic.etudiant_id = e.id
            WHERE ic.cours_id = :cours_id
        ");
        $stmt->execute(['cours_id' => $coursId]);
        $etudiants = $stmt->fetchAll();
        
        $response->json($etudiants);
    }

    public function questionMur(Request $request, Response $response): void
    {
        $data = $request->getBody();
        
        $stmt = $this->db->prepare("
            INSERT INTO mur_pedagogique (contenu, user_id, cours_id, created_at) 
            VALUES (:contenu, :user_id, :cours_id, NOW())
        ");
        
        $success = $stmt->execute([
            'contenu' => $data['contenu'],
            'user_id' => SessionHelper::getUserId(),
            'cours_id' => $data['cours_id']
        ]);
        
        $response->json(['success' => $success]);
    }
}