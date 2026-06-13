<?php

class Database {
    private $host = 'localhost';
    private $dbname = 'fasichat';
    private $username = 'root';
    private $password = 'Mandom+243';

    private ? PDO $conn = null;


    public function __construct() {
        $this->connect();
    }


    private function connect(){
        try{
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);

            $this->conn->setAttribute(
                PDO::ATTR_ERRMODE, 
                PDO::ERRMODE_EXCEPTION
            );

            $this->conn->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE, 
                PDO::FETCH_ASSOC
            );

            // echo "Connexion réussie à la base de données.";
        } catch (PDOException $e) {
            // echo "Erreur de connexion : " . $e->getMessage();
            throw $e;
        }
    }

    public function getConnection() {
        return $this->conn;
    }

}

