<?php
namespace Controllers;

use Core\Controller;
use Core\Request;
use Core\Response;
use Helpers\SessionHelper;
use Services\MessageService;
use database\Database;

class MessageController extends Controller
{
    private MessageService $messageService;

    public function __construct()
    {
        $dbConfig = require __DIR__ . '/../../config/database.php';
        $database = new Database($dbConfig);
        $this->messageService = new MessageService($database->getConnection());
    }

    public function send(Request $request, Response $response): void
    {
        $data = $request->getBody();
        $success = $this->messageService->sendMessage($data);
        $response->json(['success' => $success]);
    }

    public function sendAudio(Request $request, Response $response): void
    {
        $data = $request->getBody();
        $files = $request->getFiles();
        
        if (!isset($files['audio']) || $files['audio']['error'] !== UPLOAD_ERR_OK) {
            $response->json(['success' => false, 'error' => 'Fichier audio requis'], 400);
            return;
        }
        
        if (empty($data['destinataire_id'])) {
            $response->json(['success' => false, 'error' => 'Destinataire requis'], 400);
            return;
        }
        
        $success = $this->messageService->sendAudioMessage(
            (int)$data['destinataire_id'],
            $data['contenu'] ?? 'Message audio',
            $files['audio'],
            isset($data['duree']) ? (int)$data['duree'] : null
        );
        
        $response->json(['success' => $success !== null, 'error' => $success === null ? 'Format non supporté' : null]);
    }

    public function sendFile(Request $request, Response $response): void
    {
        $data = $request->getBody();
        $files = $request->getFiles();
        
        if (!isset($files['file']) || $files['file']['error'] !== UPLOAD_ERR_OK) {
            $response->json(['success' => false, 'error' => 'Fichier requis'], 400);
            return;
        }
        
        if (empty($data['destinataire_id'])) {
            $response->json(['success' => false, 'error' => 'Destinataire requis'], 400);
            return;
        }
        
        $success = $this->messageService->sendFileMessage(
            (int)$data['destinataire_id'],
            $data['contenu'] ?? 'Fichier joint',
            $files['file']
        );
        
        $response->json(['success' => $success !== null, 'error' => $success === null ? 'Type de fichier non supporté' : null]);
    }

    public function poll(Request $request, Response $response): void
    {
        $type = $request->getQueryParams()['type'] ?? 'prive';
        $id = (int)($request->getQueryParams()['id'] ?? 0);
        
        $messages = $this->messageService->getMessages($type, $id);
        
        foreach ($messages as &$message) {
            if (!empty($message['fichier_chemin'])) {
                $message['fichier_url'] = '/FasiChatClassroom/public/assets/uploads/' . $message['fichier_chemin'];
            }
            if (!empty($message['duree_audio'])) {
                $message['duree_formatee'] = gmdate("i:s", $message['duree_audio']);
            }
        }
        
        $response->json($messages);
    }
}