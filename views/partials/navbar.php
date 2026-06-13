<nav class="navbar">
    <div class="navbar-brand">
        <a href="<?= \Helpers\ViewHelper::url('dashboard/' . $_SESSION['user_role']) ?>">
            FasiChat Classroom
        </a>
    </div>
    <div class="navbar-menu">
        <div class="navbar-end">
            <span class="navbar-user">
                <?= \Helpers\ViewHelper::escape($_SESSION['user']['prenom'] ?? '') ?> 
                <?= \Helpers\ViewHelper::escape($_SESSION['user']['nom'] ?? '') ?>
            </span>
            <a href="<?= \Helpers\ViewHelper::url('logout') ?>" class="btn-logout">
                Déconnexion
            </a>
        </div>
    </div>
</nav>