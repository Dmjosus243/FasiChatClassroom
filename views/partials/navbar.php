<nav class="navbar">
    <div class="navbar-brand">
        <a href="/FasiChatClassroom/public/dashboard/<?= $_SESSION['user_role'] ?>">FasiChat Classroom</a>
    </div>
    <div class="navbar-menu">
        <span class="navbar-user"><?= htmlspecialchars($_SESSION['user']['prenom'] ?? '') ?> <?= htmlspecialchars($_SESSION['user']['nom'] ?? '') ?></span>
        <a href="/FasiChatClassroom/public/logout" class="btn-logout">Déconnexion</a>
    </div>
</nav>