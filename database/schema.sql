CREATE DATABASE IF NOT EXISTS fasichat;
USE fasichat;

-- 1. Table des utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    mot_de_passe VARCHAR(255),
    role VARCHAR(50) 
);

-- 2. Table des promotions (L1, L2, L3...)
CREATE TABLE IF NOT EXISTS promotions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL
);

-- 3. Table des cours
CREATE TABLE IF NOT EXISTS cours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    promotion_id INT,
    FOREIGN KEY (promotion_id) REFERENCES promotions(id)
);

-- 4. Table des messages
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    expediteur_id INT,
    contenu TEXT,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    type VARCHAR(20), -- 'prive', 'public', 'mur', 'convocation'
    cours_id INT NULL, -- Utilisé si type = 'mur'
    promotion_id INT NULL, -- Utilisé si type = 'public'
    FOREIGN KEY (expediteur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (cours_id) REFERENCES cours(id),
    FOREIGN KEY (promotion_id) REFERENCES promotions(id)
);

-- 5. Table des destinataires (pour les messages privés)
CREATE TABLE IF NOT EXISTS messages_destinataires (
    message_id INT,
    destinataire_id INT,
    lu BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (message_id, destinataire_id),
    FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE,
    FOREIGN KEY (destinataire_id) REFERENCES utilisateurs(id)
);

-- 6. Table des annonces (Valve)
CREATE TABLE IF NOT EXISTS annonces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255),
    contenu TEXT,
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
    auteur_id INT,
    FOREIGN KEY (auteur_id) REFERENCES utilisateurs(id)
);

-- 7. Table des convocations
CREATE TABLE IF NOT EXISTS convocations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message_id INT,
    objet VARCHAR(255),
    date_heure DATETIME,
    lieu VARCHAR(255),
    FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE
);

-- 8. Insertion des comptes de test
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES
('Test', 'Etudiant', 'etudiant@test.com', '$2y$12$C4REsUvXwppW89MN//Oheus5sb3tn7YyQVlHuXYH37iyjlPdJK7FK', 'etudiant'),
('Test', 'Enseignant', 'enseignant@test.com', '$2y$12$C4REsUvXwppW89MN//Oheus5sb3tn7YyQVlHuXYH37iyjlPdJK7FK', 'enseignant'),
('Test', 'Assistant', 'assistant@test.com', '$2y$12$C4REsUvXwppW89MN//Oheus5sb3tn7YyQVlHuXYH37iyjlPdJK7FK', 'assistant'),
('Test', 'Doyen', 'doyen@test.com', '$2y$12$C4REsUvXwppW89MN//Oheus5sb3tn7YyQVlHuXYH37iyjlPdJK7FK', 'doyen'),
('Test', 'Vice-Doyen', 'vicedoyen@test.com', '$2y$12$C4REsUvXwppW89MN//Oheus5sb3tn7YyQVlHuXYH37iyjlPdJK7FK', 'vice-doyen'),
('Test', 'Apparitaire', 'apparitaire@test.com', '$2y$12$C4REsUvXwppW89MN//Oheus5sb3tn7YyQVlHuXYH37iyjlPdJK7FK', 'apparitaire');

-- Insertion de quelques données de base pour les tests
INSERT INTO promotions (nom) VALUES ('L1 Info'), ('L2 FASI'), ('L3 Info');
INSERT INTO cours (nom, promotion_id) VALUES ('PHP POO', 2), ('Cybersécurité', 3);
