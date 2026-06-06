<?php

class Message {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Envoyer un message (privé, public ou mur)
    public function envoyer($expediteurId, $contenu, $type, $destinataireId = null, $coursId = null, $promotionId = null) {
        try {
            $this->db->beginTransaction();

            // 1. Insertion du message principal
            $query = "INSERT INTO messages (expediteur_id, contenu, type, cours_id, promotion_id) 
                      VALUES (:expediteur_id, :contenu, :type, :cours_id, :promotion_id)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'expediteur_id' => $expediteurId,
                'contenu' => $contenu,
                'type' => $type,
                'cours_id' => $coursId,
                'promotion_id' => $promotionId
            ]);

            $messageId = $this->db->lastInsertId();

            // 2. Si c'est un message privé, on lie le destinataire
            if ($type === 'prive' && $destinataireId !== null) {
                $queryDest = "INSERT INTO messages_destinataires (message_id, destinataire_id) 
                              VALUES (:message_id, :destinataire_id)";
                $stmtDest = $this->db->prepare($queryDest);
                $stmtDest->execute([
                    'message_id' => $messageId,
                    'destinataire_id' => $destinataireId
                ]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Récupérer la conversation privée entre deux utilisateurs
    public function recupererPrives($user1Id, $user2Id) {
        $query = "SELECT m.*, u.nom, u.prenom 
                  FROM messages m
                  JOIN utilisateurs u ON m.expediteur_id = u.id
                  LEFT JOIN messages_destinataires md ON m.id = md.message_id
                  WHERE m.type = 'prive' 
                    AND (
                        (m.expediteur_id = :u1 AND md.destinataire_id = :u2)
                        OR 
                        (m.expediteur_id = :u2 AND md.destinataire_id = :u1)
                    )
                  ORDER BY m.date_envoi ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['u1' => $user1Id, 'u2' => $user2Id]);
        return $stmt->fetchAll();
    }

    // Récupérer les messages publics d'une promotion
    public function recupererPublics($promotionId) {
        $query = "SELECT m.*, u.nom, u.prenom, u.role 
                  FROM messages m
                  JOIN utilisateurs u ON m.expediteur_id = u.id
                  WHERE m.type = 'public' AND m.promotion_id = :promotion_id
                  ORDER BY m.date_envoi ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['promotion_id' => $promotionId]);
        return $stmt->fetchAll();
    }

    // Récupérer les messages du mur pédagogique d'un cours
    public function recupererMur($coursId) {
        $query = "SELECT m.*, u.nom, u.prenom, u.role 
                  FROM messages m
                  JOIN utilisateurs u ON m.expediteur_id = u.id
                  WHERE m.type = 'mur' AND m.cours_id = :cours_id
                  ORDER BY m.date_envoi ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['cours_id' => $coursId]);
        return $stmt->fetchAll();
    }
}
