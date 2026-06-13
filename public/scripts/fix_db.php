<?php
require_once __DIR__ . '/../../bootstrap.php';

use database\Database;

$db = (new Database())->getConnection();

// Script pour corriger/ajouter des tables
$sql = "
    -- Table pour les annonces Valve
    CREATE TABLE IF NOT EXISTS annonces_valve (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titre VARCHAR(255) NOT NULL,
        contenu TEXT NOT NULL,
        user_id INT NOT NULL,
        created_at DATETIME NOT NULL,
        updated_at DATETIME,
        FOREIGN KEY (user_id) REFERENCES utilisateurs(id)
    );
    
    -- Table pour le mur pédagogique
    CREATE TABLE IF NOT EXISTS mur_pedagogique (
        id INT AUTO_INCREMENT PRIMARY KEY,
        contenu TEXT NOT NULL,
        user_id INT NOT NULL,
        cours_id INT NOT NULL,
        created_at DATETIME NOT NULL,
        FOREIGN KEY (user_id) REFERENCES utilisateurs(id),
        FOREIGN KEY (cours_id) REFERENCES cours(id)
    );
";

try {
    $db->exec($sql);
    echo "Base de données mise à jour avec succès !\n";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}