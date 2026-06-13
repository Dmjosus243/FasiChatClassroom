<?php
namespace Models;

class Utilisateur
{
    protected $db;
    protected $data;
    protected $id;

    public function __construct($db, $userData = null)
    {
        $this->db = $db;
        $this->data = $userData;
        $this->id = $userData['id'] ?? null;
    }
}