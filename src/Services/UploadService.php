<?php
namespace Services;

use Helpers\SecurityHelper;

class UploadService
{
    private string $uploadPath;
    private array $allowedTypes = [
        'image' => ['jpg', 'jpeg', 'png', 'gif'],
        'audio' => ['mp3', 'wav', 'ogg', 'webm'],
        'document' => ['pdf', 'doc', 'docx', 'txt']
    ];
    private int $maxSize;

    public function __construct()
    {
        $this->uploadPath = UPLOAD_PATH;
        $this->maxSize = MAX_FILE_SIZE;
    }

    public function upload(array $file, string $type = 'document'): ?array
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        if (!$this->validateFile($file, $type)) {
            return null;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $subdirectory = $type . 's/';
        $targetPath = $this->uploadPath . $subdirectory;
        
        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0777, true);
        }

        $targetFile = $targetPath . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return [
                'filename' => $filename,
                'original_name' => $file['name'],
                'path' => $subdirectory . $filename,
                'size' => $file['size'],
                'type' => $type,
                'extension' => $extension
            ];
        }

        return null;
    }

    private function validateFile(array $file, string $type): bool
    {
        if ($file['size'] > $this->maxSize) {
            return false;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, $this->allowedTypes[$type] ?? [])) {
            return false;
        }

        return true;
    }
}