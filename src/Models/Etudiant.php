<?php
namespace Models;

class Etudiant extends Utilisateur {
    private $db;

    public function __construct($db, $data = []) {
        parent::__construct($db, $data);
        $this->db = $db;
    }
    
}
