<?php
// src/Models/Enseignant.php

class Enseignant extends Utilisateur {
    private $db;

    public function __construct($db, $data = []) {
        parent::__construct($data);
        $this->db = $db;
    }

    // Méthode pour récupérer la liste des cours assignés à cet enseignant
    public function getMesCours() {
        $query = "SELECT c.* FROM cours c 
                  JOIN enseignant_cours ec ON c.id = ec.cours_id 
                  WHERE ec.enseignant_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $this->id]);
        return $stmt->fetchAll();
    }
}
