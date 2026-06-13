USE fasichat;

-- 1. Ajouter les colonnes pour l'audio et les fichiers dans messages
ALTER TABLE messages ADD COLUMN IF NOT EXISTS type_media ENUM('text', 'audio', 'file') DEFAULT 'text';
ALTER TABLE messages ADD COLUMN IF NOT EXISTS duree_audio INT NULL;

-- 2. Ajouter la colonne durée à la table fichiers
ALTER TABLE fichiers ADD COLUMN IF NOT EXISTS duree INT NULL;

-- 3. Créer la table mur_pedagogique (si elle n'existe pas)
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

-- 4. Créer la table annonces_valve (si elle n'existe pas)
CREATE TABLE IF NOT EXISTS annonces_valve (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- 5. Créer la table convocation_destinataires (si elle n'existe pas)
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

-- 6. Ajouter la colonne created_by à convocations (si elle n'existe pas)
ALTER TABLE convocations ADD COLUMN IF NOT EXISTS created_by INT NULL;

-- 7. Ajouter la clé étrangère pour created_by (si elle n'existe pas)
-- Note: MySQL ignore IF NOT EXISTS pour les FOREIGN KEY, on vérifie d'abord
SET @exist = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS 
              WHERE CONSTRAINT_SCHEMA = 'fasichat' 
              AND TABLE_NAME = 'convocations' 
              AND CONSTRAINT_NAME = 'convocations_ibfk_2');

SET @sql = IF(@exist = 0, 'ALTER TABLE convocations ADD FOREIGN KEY (created_by) REFERENCES utilisateurs(id) ON DELETE CASCADE', 'SELECT "Foreign key already exists"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 8. Ajouter des index pour améliorer les performances
ALTER TABLE messages ADD INDEX idx_expediteur (expediteur_id);
ALTER TABLE messages ADD INDEX idx_destinataire (destinataire_id);
ALTER TABLE messages ADD INDEX idx_created (created_at);
ALTER TABLE messages ADD INDEX idx_type (type);

ALTER TABLE convocations ADD INDEX idx_date (date_convocation);
ALTER TABLE convocations ADD INDEX idx_created_by (created_by);

ALTER TABLE annonces_valve ADD INDEX idx_created (created_at);

-- ============================================
-- FIN DE LA MISE À JOUR
-- ============================================