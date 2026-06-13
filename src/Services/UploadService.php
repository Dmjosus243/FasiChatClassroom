<?php
namespace Services;

class UploadService
{
    private string $uploadPath;
    private array $allowedTypes = [
        'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'audio' => ['mp3', 'wav', 'ogg', 'webm', 'm4a', 'aac'],
        'document' => ['pdf', 'doc', 'docx', 'txt', 'zip']
    ];
    private array $allowedMimeTypes = [
        'audio' => ['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/webm', 'audio/mp4'],
        'image' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        'document' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain', 'application/zip']
    ];

    public function __construct()
    {
        $this->uploadPath = UPLOAD_PATH;
    }

    public function upload(array $file, string $type = 'document'): ?array
    {
        if ($file['error'] !== UPLOAD_ERR_OK) return null;
        if (!$this->validateFile($file, $type)) return null;

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = uniqid() . '.' . $extension;
        $subDir = $type . 's/';
        $targetPath = $this->uploadPath . $subDir;
        
        if (!is_dir($targetPath)) mkdir($targetPath, 0777, true);

        $targetFile = $targetPath . $filename;
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return [
                'filename' => $filename,
                'original_name' => $file['name'],
                'path' => $subDir . $filename,
                'size' => $file['size'],
                'type' => $type,
                'extension' => $extension
            ];
        }
        return null;
    }

    private function validateFile(array $file, string $type): bool
    {
        $maxSize = ($type === 'audio') ? MAX_AUDIO_SIZE : MAX_FILE_SIZE;
        if ($file['size'] > $maxSize) return false;

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedTypes[$type] ?? [])) return false;

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        return in_array($mimeType, $this->allowedMimeTypes[$type] ?? []);
    }
}