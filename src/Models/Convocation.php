<?php

class Convocation {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Récupérer toutes les convocations envoyées aux enseignants/assistants
    public function recupererToutes() {
        $query = "SELECT c.id, c.objet, c.date_heure, c.lieu, m.contenu, u.nom as expediteur_nom, u.prenom as expediteur_prenom 
                  FROM convocations c
                  JOIN messages m ON c.message_id = m.id
                  JOIN utilisateurs u ON m.expediteur_id = u.id
                  ORDER BY c.date_heure DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
