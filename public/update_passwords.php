<?php
// public/update_passwords.php
require_once __DIR__ . '/../database/Database.php';

try {
    $dbInstance = new Database();
    $db = $dbInstance->getConnection();

    if ($db) {
        // S'assurer que la table convocations existe bien au cas où le schéma n'a pas été réimporté
        $db->exec("CREATE TABLE IF NOT EXISTS convocations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            message_id INT,
            objet VARCHAR(255),
            date_heure DATETIME,
            lieu VARCHAR(255),
            FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE
        )");

        // On génère le hash sécurisé pour le mot de passe "password123"
        $hash = password_hash('password123', PASSWORD_BCRYPT);
        
        // Vider proprement la table pour repartir à neuf
        $db->exec("DELETE FROM utilisateurs");
        $db->exec("ALTER TABLE utilisateurs AUTO_INCREMENT = 1");

        // Insérer tous les comptes de test requis par votre sujet de TP
        $stmt = $db->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
        
        // Comptes pédagogiques
        $stmt->execute(['Test', 'Etudiant', 'etudiant@test.com', $hash, 'etudiant']);
        $stmt->execute(['Test', 'Enseignant', 'enseignant@test.com', $hash, 'enseignant']);
        $stmt->execute(['Test', 'Assistant', 'assistant@test.com', $hash, 'assistant']);
        
        // Comptes administratifs
        $stmt->execute(['Test', 'Doyen', 'doyen@test.com', $hash, 'doyen']);
        $stmt->execute(['Test', 'Vice-Doyen', 'vicedoyen@test.com', $hash, 'vice-doyen']);
        $stmt->execute(['Test', 'Apparitaire', 'apparitaire@test.com', $hash, 'apparitaire']);
        
        echo "<h3 style='color: green; font-family: sans-serif;'>✅ Tous les comptes de test ont été créés/mis à jour avec le mot de passe 'password123' !</h3>";
        echo "<table border='1' cellpadding='8' style='border-collapse: collapse; font-family: sans-serif;'>
                <tr style='background: #f3f4f6;'>
                    <th>Rôle</th>
                    <th>Email</th>
                    <th>Mot de passe</th>
                </tr>
                <tr><td>Étudiant</td><td>etudiant@test.com</td><td>password123</td></tr>
                <tr><td>Enseignant</td><td>enseignant@test.com</td><td>password123</td></tr>
                <tr><td>Assistant</td><td>assistant@test.com</td><td>password123</td></tr>
                <tr><td>Doyen</td><td>doyen@test.com</td><td>password123</td></tr>
                <tr><td>Vice-Doyen</td><td>vicedoyen@test.com</td><td>password123</td></tr>
                <tr><td>Apparitaire</td><td>apparitaire@test.com</td><td>password123</td></tr>
              </table>";
        echo "<p style='font-family: sans-serif; margin-top: 15px;'><a href='/FasiChatClassroom/public/login'>👉 Aller à la page de connexion</a></p>";
    } else {
        echo "<h3 style='color: red; font-family: sans-serif;'>❌ Connexion à la base de données impossible.</h3>";
    }
} catch (Exception $e) {
    echo "<h3 style='color: red; font-family: sans-serif;'>❌ Erreur : " . $e->getMessage() . "</h3>";
}
