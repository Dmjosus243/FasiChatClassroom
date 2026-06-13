<?php
namespace Models;

class Assistant extends Enseignant {
    
    public function __construct($db, $data = []) {
        parent::__construct($db, $data);
    }
}
