<?php
// public/fix_db.php
require_once __DIR__ . '/../database/Database.php';

try {
    $dbInstance = new Database();
    $db = $dbInstance->getConnection();

    // 1. Ajouter la table fichiers si elle manque
    $db->exec("CREATE TABLE IF NOT EXISTS fichiers (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        nom_origine VARCHAR(255), 
        nom_stockage VARCHAR(255), 
        chemin VARCHAR(255), 
        type_mime VARCHAR(100), 
        taille INT, 
        date_upload DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 2. Ajouter la colonne fichier_id à messages si elle n'existe pas
    $columns = $db->query("SHOW COLUMNS FROM messages LIKE 'fichier_id'")->fetchAll();
    if (empty($columns)) {
        $db->exec("ALTER TABLE messages ADD COLUMN fichier_id INT NULL");
        echo "✅ Colonne 'fichier_id' ajoutée avec succès.<br>";
    } else {
        echo "ℹ️ La colonne 'fichier_id' existe déjà.<br>";
    }

    // 3. Ajouter la contrainte de clé étrangère
    try {
        $db->exec("ALTER TABLE messages ADD FOREIGN KEY (fichier_id) REFERENCES fichiers(id) ON DELETE SET NULL");
        echo "✅ Contrainte de clé étrangère ajoutée.<br>";
    } catch (PDOException $e) {
        echo "⚠️ Contrainte déjà présente ou erreur : " . $e->getMessage() . "<br>";
    }

    echo "<h3 style='color:green;'>Base de données réparée ! Vous pouvez maintenant supprimer ce fichier 'fix_db.php'.</h3>";

} catch (Exception $e) {
    echo "<h3 style='color:red;'>❌ Erreur critique : " . $e->getMessage() . "</h3>";
}
