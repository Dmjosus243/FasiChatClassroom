<?php require_once __DIR__ . '/../partials/header.php'; ?>
<div class="error-container">
    <h1>500 - Erreur interne</h1>
    <p>Une erreur est survenue. Veuillez réessayer plus tard.</p>
    <a href="<?= \Helpers\ViewHelper::url('') ?>" class="btn">Retour à l'accueil</a>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>