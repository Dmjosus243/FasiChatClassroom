<?php
namespace Services;

use Models\Message;
use Models\Fichier;
use Helpers\SessionHelper;

class MessageService
{
    private Message $messageModel;
    private Fichier $fichierModel;

    public function __construct($db)
    {
        $this->messageModel = new Message($db);
        $this->fichierModel = new Fichier($db);
    }

    public function sendMessage(array $data): bool
    {
        $userId = SessionHelper::getUserId();
        
        return $this->messageModel->envoyer(
            $userId,
            $data['contenu'] ?? '',
            $data['type'] ?? 'prive',
            $data['destinataire_id'] ?? null,
            $data['cours_id'] ?? null,
            $data['promotion_id'] ?? null,
            $data['fichier_id'] ?? null
        );
    }

    public function getMessages(string $type, int $id): array
    {
        $userId = SessionHelper::getUserId();
        
        if ($type === 'prive') {
            return $this->messageModel->recupererPrives($userId, $id);
        } elseif ($type === 'public') {
            return $this->messageModel->recupererPublics($id);
        } else {
            return $this->messageModel->recupererMur($id);
        }
    }

    public function uploadFile(array $file): ?int
    {
        return $this->fichierModel->upload($file);
    }
}