<?php
require_once __DIR__ . '/../../bootstrap.php';

use database\Database;
use Helpers\SecurityHelper;

$db = (new Database())->getConnection();

$stmt = $db->query("SELECT id, mot_de_passe FROM utilisateurs WHERE mot_de_passe NOT LIKE '$2y$%'");
$users = $stmt->fetchAll();

foreach ($users as $user) {
    if (!empty($user['mot_de_passe'])) {
        $newHash = SecurityHelper::hashPassword($user['mot_de_passe']);
        $update = $db->prepare("UPDATE utilisateurs SET mot_de_passe = :hash WHERE id = :id");
        $update->execute(['hash' => $newHash, 'id' => $user['id']]);
        echo "Mot de passe mis à jour pour l'utilisateur ID: {$user['id']}\n";
    }
}

echo "Terminé !\n";