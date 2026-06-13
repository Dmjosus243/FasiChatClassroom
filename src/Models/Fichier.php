<?php
namespace Models;

class Fichier
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function upload($file)
    {
        $targetDir = UPLOAD_PATH . 'documents/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = uniqid() . '_' . basename($file['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $sql = "INSERT INTO fichiers (nom, nom_original, chemin, type, taille) 
                    VALUES (:nom, :nom_original, :chemin, 'document', :taille)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'nom' => $fileName,
                'nom_original' => $file['name'],
                'chemin' => 'documents/' . $fileName,
                'taille' => $file['size']
            ]);
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function save($fileData)
    {
        $sql = "INSERT INTO fichiers (nom, nom_original, chemin, type, taille, duree) 
                VALUES (:nom, :nom_original, :chemin, :type, :taille, :duree)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nom' => $fileData['filename'],
            'nom_original' => $fileData['original_name'],
            'chemin' => $fileData['path'],
            'type' => $fileData['type'],
            'taille' => $fileData['size'],
            'duree' => $fileData['duree'] ?? null
        ]);
        return $this->db->lastInsertId();
    }
}