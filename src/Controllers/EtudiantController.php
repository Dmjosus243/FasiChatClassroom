<?php
namespace Controllers;

use Core\Controller;
use Core\Request;
use Core\Response;
use Helpers\SessionHelper;
use database\Database;

class EtudiantController extends Controller
{
    private $db;

    public function __construct()
    {
        $dbConfig = require __DIR__ . '/../../config/database.php';
        $database = new Database($dbConfig);
        $this->db = $database->getConnection();
    }

    public function getPromotion(Request $request, Response $response): void
    {
        $userId = SessionHelper::getUserId();
        $stmt = $this->db->prepare("SELECT p.* FROM promotions p JOIN etudiants e ON e.promotion_id = p.id WHERE e.user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $response->json($stmt->fetch());
    }

    public function getCours(Request $request, Response $response): void
    {
        $userId = SessionHelper::getUserId();
        $stmt = $this->db->prepare("SELECT c.* FROM cours c JOIN inscription_cours ic ON ic.cours_id = c.id WHERE ic.etudiant_id = (SELECT id FROM etudiants WHERE user_id = :user_id)");
        $stmt->execute(['user_id' => $userId]);
        $response->json($stmt->fetchAll());
    }
}