-- Correction de la faute de frappe ici
CREATE DATABASE IF NOT EXISTS fasichat;
USE fasichat;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('etudiant', 'enseignant', 'assistant', 'apparitaire', 'doyen', 'vice-doyen') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des promotions
CREATE TABLE IF NOT EXISTS promotions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    annee INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des étudiants
CREATE TABLE IF NOT EXISTS etudiants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    matricule VARCHAR(50) NOT NULL UNIQUE,
    promotion_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (promotion_id) REFERENCES promotions(id) ON DELETE CASCADE
);

-- Table des cours
CREATE TABLE IF NOT EXISTS cours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    enseignant_id INT,
    assistant_id INT,
    promotion_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (enseignant_id) REFERENCES utilisateurs(id) ON DELETE SET NULL,
    FOREIGN KEY (assistant_id) REFERENCES utilisateurs(id) ON DELETE SET NULL,
    FOREIGN KEY (promotion_id) REFERENCES promotions(id) ON DELETE CASCADE
);

-- Table d'inscription des cours
CREATE TABLE IF NOT EXISTS inscription_cours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etudiant_id INT NOT NULL,
    cours_id INT NOT NULL,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
    FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE,
    UNIQUE KEY unique_inscription (etudiant_id, cours_id)
);

-- Table des fichiers
CREATE TABLE IF NOT EXISTS fichiers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    nom_original VARCHAR(255) NOT NULL,
    chemin VARCHAR(500) NOT NULL,
    type ENUM('image', 'audio', 'document') NOT NULL,
    taille INT NOT NULL,
    duree INT NULL, -- Utile pour les audios (en secondes)
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des messages
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    expediteur_id INT NOT NULL,
    destinataire_id INT NULL, -- NULL si c'est un message de groupe/public
    contenu TEXT NOT NULL,
    type ENUM('prive', 'public', 'groupe', 'mur', 'convocation') DEFAULT 'prive',
    type_media ENUM('text', 'audio', 'file') DEFAULT 'text',
    cours_id INT NULL,
    promotion_id INT NULL,
    fichier_id INT NULL,
    duree_audio INT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    lu BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (expediteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (destinataire_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE,
    FOREIGN KEY (promotion_id) REFERENCES promotions(id) ON DELETE CASCADE,
    FOREIGN KEY (fichier_id) REFERENCES fichiers(id) ON DELETE SET NULL
);

-- Table du mur pédagogique
CREATE TABLE IF NOT EXISTS mur_pedagogique (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contenu TEXT NOT NULL,
    user_id INT NOT NULL,
    cours_id INT NOT NULL,
    fichier_id INT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE,
    FOREIGN KEY (fichier_id) REFERENCES fichiers(id) ON DELETE SET NULL
);

-- Table des convocations
CREATE TABLE IF NOT EXISTS convocations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    objet VARCHAR(255) NOT NULL,
    date_convocation DATE NOT NULL,
    heure_convocation TIME NOT NULL,
    lieu VARCHAR(255) NOT NULL,
    message TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INT NOT NULL,
    FOREIGN KEY (created_by) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table des destinataires de convocations
CREATE TABLE IF NOT EXISTS convocation_destinataires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    convocation_id INT NOT NULL,
    user_id INT NOT NULL,
    lu BOOLEAN DEFAULT FALSE,
    lu_at DATETIME NULL,
    FOREIGN KEY (convocation_id) REFERENCES convocations(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    UNIQUE KEY unique_destinataire (convocation_id, user_id)
);

-- Table des annonces Valve
CREATE TABLE IF NOT EXISTS annonces_valve (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Insertion des données de test
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES
('Admin', 'Système', 'admin@fasichat.com', '$2y$10$YourHashedPasswordHere', 'doyen'),
('Dupont', 'Jean', 'jean.dupont@fasichat.com', '$2y$10$YourHashedPasswordHere', 'enseignant'),
('Martin', 'Marie', 'marie.martin@fasichat.com', '$2y$10$YourHashedPasswordHere', 'etudiant');

-- Insertion d'une promotion
INSERT INTO promotions (nom, annee) VALUES ('Licence Informatique', 2024);