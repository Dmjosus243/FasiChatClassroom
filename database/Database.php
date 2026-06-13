<?php
namespace database;

use PDO;
use PDOException;

class Database
{
    private $connection;
    private $config;

    public function __construct($config = null)
    {
        if ($config === null) {
            $this->config = [
                'host' => 'localhost',
                'dbname' => 'fasichat',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8mb4'
            ];
        } else {
            $this->config = $config;
        }
    }

    public function getConnection()
    {
        if ($this->connection === null) {
            try {
                $this->connection = new PDO(
                    "mysql:host={$this->config['host']};dbname={$this->config['dbname']};charset={$this->config['charset']}",
                    $this->config['username'],
                    $this->config['password'],
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
                );
            } catch (PDOException $e) {
                die("Erreur connexion: " . $e->getMessage());
            }
        }
        return $this->connection;
    }
}