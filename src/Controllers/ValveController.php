<?php
namespace Controllers;

use Core\Controller;
use Core\Request;
use Core\Response;
use Helpers\SessionHelper;
use database\Database;

class ValveController extends Controller
{
    private $db;

    public function __construct()
    {
        $dbConfig = require __DIR__ . '/../../config/database.php';
        $database = new Database($dbConfig);
        $this->db = $database->getConnection();
    }

    public function show(Request $request, Response $response): void
    {
        $stmt = $this->db->query("SELECT * FROM annonces_valve ORDER BY created_at DESC");
        $annonces = $stmt->fetchAll();
        $this->render('valve', ['annonces' => $annonces, 'user' => SessionHelper::getUser()]);
    }

    public function publish(Request $request, Response $response): void
    {
        $data = $request->getBody();
        if (empty($data['titre']) || empty($data['contenu'])) {
            $response->json(['success' => false, 'error' => 'Titre et contenu requis'], 400);
            return;
        }
        
        $stmt = $this->db->prepare("INSERT INTO annonces_valve (titre, contenu, user_id, created_at) VALUES (:titre, :contenu, :user_id, NOW())");
        $success = $stmt->execute(['titre' => $data['titre'], 'contenu' => $data['contenu'], 'user_id' => SessionHelper::getUserId()]);
        $response->json(['success' => $success]);
    }

    public function update(Request $request, Response $response): void
    {
        $id = $request->getParam('id');
        $data = $request->getBody();
        $stmt = $this->db->prepare("UPDATE annonces_valve SET titre = :titre, contenu = :contenu WHERE id = :id");
        $success = $stmt->execute(['id' => $id, 'titre' => $data['titre'], 'contenu' => $data['contenu']]);
        $response->json(['success' => $success]);
    }

    public function delete(Request $request, Response $response): void
    {
        $id = $request->getParam('id');
        $stmt = $this->db->prepare("DELETE FROM annonces_valve WHERE id = :id");
        $success = $stmt->execute(['id' => $id]);
        $response->json(['success' => $success]);
    }
}