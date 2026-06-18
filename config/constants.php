<?php
// Définition des rôles utilisateurs
define('ROLE_ETUDIANT', 'etudiant');
define('ROLE_ENSEIGNANT', 'enseignant');
define('ROLE_ASSISTANT', 'assistant');
define('ROLE_APPARITAIRE', 'apparitaire');
define('ROLE_DOYEN', 'doyen');
define('ROLE_VICE_DOYEN', 'vice-doyen');

// Types de messages
define('MESSAGE_PRIVE', 'prive');
define('MESSAGE_PUBLIC', 'public');
define('MESSAGE_MUR', 'mur');
define('MESSAGE_AUDIO', 'audio');
define('MESSAGE_FILE', 'file');

// Types de fichiers
define('FILE_TYPE_IMAGE', 'image');
define('FILE_TYPE_AUDIO', 'audio');
define('FILE_TYPE_DOCUMENT', 'document');

// Limites de fichiers (en bytes)
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('MAX_AUDIO_SIZE', 5 * 1024 * 1024); // 5MB

// Chemins
define('BASE_PATH', dirname(__DIR__));
define('UPLOAD_PATH', BASE_PATH . '/public/assets/uploads/');
define('VIEWS_PATH', BASE_PATH . '/views/');