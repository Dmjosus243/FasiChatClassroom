<?php
namespace Models;

class Enseignant extends Utilisateur {
    private $db;

    public function __construct($db, $data = []) {
        parent::__construct($db, $data);
        $this->db = $db;
    }

    public function getMesCours() {
        $query = "SELECT c.* FROM cours c 
                  JOIN enseignant_cours ec ON c.id = ec.cours_id 
                  WHERE ec.enseignant_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $this->id]);
        return $stmt->fetchAll();
    }
}
