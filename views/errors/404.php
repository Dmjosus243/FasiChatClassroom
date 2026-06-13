<?php require_once __DIR__ . '/../partials/header.php'; ?>
<div class="error-container">
    <h1>404 - Page non trouvée</h1>
    <p>La page que vous recherchez n'existe pas.</p>
    <a href="<?= \Helpers\ViewHelper::url('') ?>" class="btn">Retour à l'accueil</a>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>