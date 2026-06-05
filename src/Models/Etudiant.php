<?php

class Etudiant extends Utilisateur {
    private $db;

    public function __construct($db, $data = []) {
        parent::__construct($data);
        $this->db = $db;
    }
    
}
