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
        $database = new Database();
        $this->messageService = new MessageService($database->getConnection());
    }

    public function send(Request $request, Response $response): void
    {
        $data = $request->getBody();
        $files = $request->getFiles();
        
        $fileId = null;
        if (isset($files['file']) && $files['file']['error'] === UPLOAD_ERR_OK) {
            $fileId = $this->messageService->uploadFile($files['file']);
            if (!$fileId) {
                $response->json(['success' => false, 'error' => 'Erreur lors de l\'upload'], 400);
                return;
            }
            $data['fichier_id'] = $fileId;
        }
        
        $success = $this->messageService->sendMessage($data);
        $response->json(['success' => $success]);
    }

    public function poll(Request $request, Response $response): void
    {
        $type = $request->getQueryParams()['type'] ?? 'prive';
        $id = $request->getQueryParams()['id'] ?? 0;
        
        $messages = $this->messageService->getMessages($type, (int)$id);
        $response->json($messages);
    }
}