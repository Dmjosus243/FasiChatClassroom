<?php
require_once __DIR__ . '/../../bootstrap.php';
use database\Database;

$db = (new Database())->getConnection();
$sqls = [
    "ALTER TABLE messages ADD COLUMN IF NOT EXISTS type_media ENUM('text', 'audio', 'file') DEFAULT 'text'",
    "ALTER TABLE messages ADD COLUMN IF NOT EXISTS duree_audio INT NULL",
    "ALTER TABLE fichiers ADD COLUMN IF NOT EXISTS duree INT NULL"
];

foreach ($sqls as $sql) {
    try {
        $db->exec($sql);
        echo "✅ Exécuté\n";
    } catch (PDOException $e) {
        echo "⚠️ " . $e->getMessage() . "\n";
    }
}
echo "✅ Base 'fasichat' mise à jour\n";