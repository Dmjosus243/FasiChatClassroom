<?php
namespace Models;

class Message
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function envoyer($expediteurId, $contenu, $type = 'prive', $destinataireId = null, $coursId = null, $promotionId = null, $fichierId = null)
    {
        $sql = "INSERT INTO messages (expediteur_id, destinataire_id, contenu, type, cours_id, promotion_id, fichier_id, type_media, created_at) 
                VALUES (:exp, :dest, :contenu, :type, :cours, :promo, :fichier, :media, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'exp' => $expediteurId,
            'dest' => $destinataireId,
            'contenu' => $contenu,
            'type' => $type,
            'cours' => $coursId,
            'promo' => $promotionId,
            'fichier' => $fichierId,
            'media' => $fichierId ? 'file' : 'text'
        ]);
    }

    public function envoyerAvecAudio($expediteurId, $contenu, $destinataireId, $fichierId, $duree = null)
    {
        $sql = "INSERT INTO messages (expediteur_id, destinataire_id, contenu, type, type_media, fichier_id, duree_audio, created_at) 
                VALUES (:exp, :dest, :contenu, 'prive', 'audio', :fichier_id, :duree, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'exp' => $expediteurId,
            'dest' => $destinataireId,
            'contenu' => $contenu,
            'fichier_id' => $fichierId,
            'duree' => $duree
        ]);
    }

    public function recupererPrives($userId, $otherUserId)
    {
        $sql = "SELECT m.*, u1.nom as expediteur_nom, u1.prenom as expediteur_prenom,
                       u2.nom as destinataire_nom, u2.prenom as destinataire_prenom,
                       f.id as fichier_id, f.nom_original as fichier_nom, f.chemin as fichier_chemin, f.type as fichier_type
                FROM messages m
                JOIN utilisateurs u1 ON m.expediteur_id = u1.id
                JOIN utilisateurs u2 ON m.destinataire_id = u2.id
                LEFT JOIN fichiers f ON m.fichier_id = f.id
                WHERE (m.expediteur_id = :user1 AND m.destinataire_id = :user2)
                   OR (m.expediteur_id = :user2 AND m.destinataire_id = :user1)
                ORDER BY m.created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user1' => $userId, 'user2' => $otherUserId]);
        return $stmt->fetchAll();
    }

    public function recupererPublics($coursId)
    {
        $sql = "SELECT m.*, u.nom, u.prenom, f.id as fichier_id, f.nom_original as fichier_nom, f.chemin as fichier_chemin
                FROM messages m JOIN utilisateurs u ON m.expediteur_id = u.id
                LEFT JOIN fichiers f ON m.fichier_id = f.id
                WHERE m.cours_id = :cours_id AND m.type = 'public'
                ORDER BY m.created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['cours_id' => $coursId]);
        return $stmt->fetchAll();
    }

    public function recupererMur($coursId)
    {
        $sql = "SELECT m.*, u.nom, u.prenom, u.role, f.id as fichier_id, f.nom_original as fichier_nom, f.chemin as fichier_chemin
                FROM mur_pedagogique m JOIN utilisateurs u ON m.user_id = u.id
                LEFT JOIN fichiers f ON m.fichier_id = f.id
                WHERE m.cours_id = :cours_id ORDER BY m.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['cours_id' => $coursId]);
        return $stmt->fetchAll();
    }
}