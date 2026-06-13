<?php
namespace Controllers;

use Core\Controller;
use Core\Request;
use Core\Response;
use database\Database;

class DoyenController extends Controller
{
    private $db;

    public function __construct()
    {
        $dbConfig = require __DIR__ . '/../../config/database.php';
        $database = new Database($dbConfig);
        $this->db = $database->getConnection();
    }

    public function getStatistiques(Request $request, Response $response): void
    {
        $stats = [];
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM utilisateurs WHERE role = 'etudiant'");
        $stats['etudiants'] = $stmt->fetch()['total'];
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM utilisateurs WHERE role = 'enseignant'");
        $stats['enseignants'] = $stmt->fetch()['total'];
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM cours");
        $stats['cours'] = $stmt->fetch()['total'];
        $response->json($stats);
    }

    public function getEnseignants(Request $request, Response $response): void
    {
        $stmt = $this->db->query("SELECT u.id, u.nom, u.prenom, u.email, COUNT(c.id) as nb_cours FROM utilisateurs u LEFT JOIN cours c ON c.enseignant_id = u.id WHERE u.role = 'enseignant' GROUP BY u.id");
        $response->json($stmt->fetchAll());
    }

    public function getAssistants(Request $request, Response $response): void
    {
        $stmt = $this->db->query("SELECT u.id, u.nom, u.prenom, u.email, COUNT(c.id) as nb_cours FROM utilisateurs u LEFT JOIN cours c ON c.assistant_id = u.id WHERE u.role = 'assistant' GROUP BY u.id");
        $response->json($stmt->fetchAll());
    }
}