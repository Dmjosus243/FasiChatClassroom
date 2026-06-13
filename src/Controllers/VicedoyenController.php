<?php
namespace Controllers;

use Core\Controller;
use Core\Request;
use Core\Response;
use Helpers\SessionHelper;
use database\Database;

class VicedoyenController extends Controller
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getStatistiques(Request $request, Response $response): void
    {
        $stats = [];
        
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM utilisateurs WHERE role = 'etudiant'");
        $stats['etudiants'] = $stmt->fetch()['total'];
        
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM utilisateurs WHERE role IN ('enseignant', 'assistant')");
        $stats['personnel'] = $stmt->fetch()['total'];
        
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM cours");
        $stats['cours'] = $stmt->fetch()['total'];
        
        $response->json($stats);
    }

    public function getConvocations(Request $request, Response $response): void
    {
        $stmt = $this->db->query("
            SELECT * FROM convocations 
            ORDER BY date_convocation DESC, heure_convocation DESC
            LIMIT 50
        ");
        $convocations = $stmt->fetchAll();
        
        $response->json($convocations);
    }
}