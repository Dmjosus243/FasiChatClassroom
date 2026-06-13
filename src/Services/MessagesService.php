<?php
namespace Services;

use Models\Message;
use Models\Fichier;
use Helpers\SessionHelper;

class MessageService
{
    private Message $messageModel;
    private Fichier $fichierModel;
    private UploadService $uploadService;

    public function __construct($db)
    {
        $this->messageModel = new Message($db);
        $this->fichierModel = new Fichier($db);
        $this->uploadService = new UploadService();
    }

    public function sendMessage(array $data): bool
    {
        return $this->messageModel->envoyer(
            SessionHelper::getUserId(),
            $data['contenu'] ?? '',
            $data['type'] ?? 'prive',
            $data['destinataire_id'] ?? null,
            $data['cours_id'] ?? null,
            $data['promotion_id'] ?? null,
            $data['fichier_id'] ?? null
        );
    }

    public function sendAudioMessage(int $destinataireId, string $contenu, array $audioFile, ?int $duree = null): ?bool
    {
        $uploadedFile = $this->uploadService->upload($audioFile, 'audio');
        if (!$uploadedFile) return null;
        
        $fichierId = $this->fichierModel->save($uploadedFile);
        if (!$fichierId) return null;
        
        return $this->messageModel->envoyerAvecAudio(
            SessionHelper::getUserId(),
            $contenu,
            $destinataireId,
            $fichierId,
            $duree
        );
    }

    public function sendFileMessage(int $destinataireId, string $contenu, array $file): ?bool
    {
        $uploadedFile = $this->uploadService->upload($file, 'document');
        if (!$uploadedFile) return null;
        
        $fichierId = $this->fichierModel->save($uploadedFile);
        if (!$fichierId) return null;
        
        return $this->messageModel->envoyer(
            SessionHelper::getUserId(),
            $contenu,
            'prive',
            $destinataireId,
            null,
            null,
            $fichierId
        );
    }

    public function getMessages(string $type, int $id): array
    {
        $userId = SessionHelper::getUserId();
        if ($type === 'prive') {
            return $this->messageModel->recupererPrives($userId, $id);
        } elseif ($type === 'public') {
            return $this->messageModel->recupererPublics($id);
        }
        return $this->messageModel->recupererMur($id);
    }
}