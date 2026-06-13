<?php
namespace Models;

class Utilisateur
{
    protected $db;
    protected $data;

    public function __construct($db, $userData = null)
    {
        $this->db = $db;
        $this->data = $userData;
    }
}