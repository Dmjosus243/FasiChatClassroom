<?php
namespace Models;

class Convocation
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create($objet, $date, $heure, $lieu, $message, $userIds, $createdBy)
    {
        $sql = "INSERT INTO convocations (objet, date_convocation, heure_convocation, lieu, message, created_by, created_at) 
                VALUES (:objet, :date, :heure, :lieu, :message, :created_by, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'objet' => $objet,
            'date' => $date,
            'heure' => $heure,
            'lieu' => $lieu,
            'message' => $message,
            'created_by' => $createdBy
        ]);
        
        $convocationId = $this->db->lastInsertId();
        
        foreach ($userIds as $userId) {
            $sql2 = "INSERT INTO convocation_destinataires (convocation_id, user_id) VALUES (:conv_id, :user_id)";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->execute(['conv_id' => $convocationId, 'user_id' => $userId]);
        }
        return true;
    }

    public function recupererToutes()
    {
        $sql = "SELECT c.*, u.nom as expediteur_nom, u.prenom as expediteur_prenom 
                FROM convocations c 
                JOIN utilisateurs u ON c.created_by = u.id 
                ORDER BY c.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}