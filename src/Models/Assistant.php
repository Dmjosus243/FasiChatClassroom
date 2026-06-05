<?php
// src/Models/Assistant.php

class Assistant extends Enseignant {
    
    public function __construct($db, $data = []) {
        parent::__construct($db, $data);
    }
}
