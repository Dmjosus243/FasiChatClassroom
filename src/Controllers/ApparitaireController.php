<?php
namespace Controllers;

use Core\Controller;
use Core\Request;
use Core\Response;
use Helpers\SessionHelper;
use database\Database;

class ApparitaireController extends Controller
{
    private $db;

    public function __construct()
    {
        $dbConfig = require __DIR__ . '/../../config/database.php';
        $database = new Database($dbConfig);
        $this->db = $database->getConnection();
    }

    public function getAnnonces(Request $request, Response $response): void
    {
        $stmt = $this->db->query("SELECT * FROM annonces_valve ORDER BY created_at DESC");
        $response->json($stmt->fetchAll());
    }

    public function createAnnonce(Request $request, Response $response): void
    {
        $data = $request->getBody();
        $stmt = $this->db->prepare("INSERT INTO annonces_valve (titre, contenu, user_id, created_at) VALUES (:titre, :contenu, :user_id, NOW())");
        $success = $stmt->execute(['titre' => $data['titre'], 'contenu' => $data['contenu'], 'user_id' => SessionHelper::getUserId()]);
        $response->json(['success' => $success]);
    }

    public function updateAnnonce(Request $request, Response $response): void
    {
        $id = $request->getParam('id');
        $data = $request->getBody();
        $stmt = $this->db->prepare("UPDATE annonces_valve SET titre = :titre, contenu = :contenu WHERE id = :id");
        $success = $stmt->execute(['id' => $id, 'titre' => $data['titre'], 'contenu' => $data['contenu']]);
        $response->json(['success' => $success]);
    }

    public function deleteAnnonce(Request $request, Response $response): void
    {
        $id = $request->getParam('id');
        $stmt = $this->db->prepare("DELETE FROM annonces_valve WHERE id = :id");
        $success = $stmt->execute(['id' => $id]);
        $response->json(['success' => $success]);
    }
}