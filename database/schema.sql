CREATE DATABASE IF NOT EXISTS fasichat;
USE fasichat;

-- 1. Table des utilisateurs (tous les rôles)
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    mot_de_passe VARCHAR(255), -- Mot de passe haché par PHP
    role VARCHAR(50) -- valeurs possibles : etudiant, enseignant, assistant, doyen, vice-doyen, apparitaire
);

-- 2. Table des messages
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    expediteur_id INT,
    contenu TEXT,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    type VARCHAR(20), -- prive, public, mur
    FOREIGN KEY (expediteur_id) REFERENCES utilisateurs(id)
);

-- 3. Table des annonces (Valve)
CREATE TABLE IF NOT EXISTS annonces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255),
    contenu TEXT,
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
    auteur_id INT,
    FOREIGN KEY (auteur_id) REFERENCES utilisateurs(id)
);

-- 3b. Table des convocations de réunions (Doyen & Vice-Doyen)
CREATE TABLE IF NOT EXISTS convocations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message_id INT,
    objet VARCHAR(255),
    date_heure DATETIME,
    lieu VARCHAR(255),
    FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE
);

-- 4. Insertion des comptes de test génériques
-- Mot de passe pour tous : "password123"
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES
('Test', 'Etudiant', 'etudiant@test.com', '$2y$12$C4REsUvXwppW89MN//Oheus5sb3tn7YyQVlHuXYH37iyjlPdJK7FK', 'etudiant'),
('Test', 'Enseignant', 'enseignant@test.com', '$2y$12$C4REsUvXwppW89MN//Oheus5sb3tn7YyQVlHuXYH37iyjlPdJK7FK', 'enseignant'),
('Test', 'Assistant', 'assistant@test.com', '$2y$12$C4REsUvXwppW89MN//Oheus5sb3tn7YyQVlHuXYH37iyjlPdJK7FK', 'assistant'),
('Test', 'Doyen', 'doyen@test.com', '$2y$12$C4REsUvXwppW89MN//Oheus5sb3tn7YyQVlHuXYH37iyjlPdJK7FK', 'doyen'),
('Test', 'Vice-Doyen', 'vicedoyen@test.com', '$2y$12$C4REsUvXwppW89MN//Oheus5sb3tn7YyQVlHuXYH37iyjlPdJK7FK', 'vice-doyen'),
('Test', 'Apparitaire', 'apparitaire@test.com', '$2y$12$C4REsUvXwppW89MN//Oheus5sb3tn7YyQVlHuXYH37iyjlPdJK7FK', 'apparitaire');
