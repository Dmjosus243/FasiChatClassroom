<?php require_once __DIR__ . '/../partials/header.php'; ?>
<div class="error-container">
    <h1>403 - Accès interdit</h1>
    <p>Vous n'avez pas les permissions nécessaires pour accéder à cette page.</p>
    <a href="<?= \Helpers\ViewHelper::url('') ?>" class="btn">Retour à l'accueil</a>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>