<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doyen') {
    header('Location: /FasiChatClassroom/public/login');
    exit();
}
$currentUser = $_SESSION['user'];
require_once __DIR__ . '/../src/Autoloader.php';
require_once __DIR__ . '/../database/Database.php';
$dbInstance = new Database();
$db = $dbInstance->getConnection();
$stmtUser = $db->query("SELECT COUNT(*) as total FROM utilisateurs");
$totalUsers = $stmtUser->fetch()['total'];
$stmtEns = $db->query("SELECT COUNT(*) as total FROM utilisateurs WHERE role IN ('enseignant','assistant')");
$totalEns = $stmtEns->fetch()['total'];
$stmtEtud = $db->query("SELECT COUNT(*) as total FROM utilisateurs WHERE role = 'etudiant'");
$totalEtud = $stmtEtud->fetch()['total'];
$stmtCours = $db->query("SELECT COUNT(*) as total FROM cours");
$totalCours = $stmtCours->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FasiChat — Doyen Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/FasiChatClassroom/public/assets/css/dashboard_admin.css">
</head>
<body>
<div class="sidebar">
  <div class="sidebar-header">
    <div class="brand-mark">🏛</div>
    <div class="brand-info"><h3>FasiChat Admin</h3><span>Espace Doyen</span></div>
  </div>
  <div class="role-badge-sidebar">
    <div class="rdot"></div>
    <span>DOYEN — Accès complet</span>
  </div>
  <div class="nav-section">
    <div class="nav-section-label">Administration</div>
    <div class="nav-item active" onclick="setNav(this)">
      <div class="nav-icon" style="background:rgba(79,163,224,0.12);">📊</div>
      <div><div class="nav-label">Tableau de bord</div><div class="nav-sub">Vue d'ensemble</div></div>
    </div>
    <div class="nav-item" onclick="location.href='/FasiChatClassroom/public/valve'">
      <div class="nav-icon" style="background:rgba(245,158,11,0.12);">📣</div>
      <div><div class="nav-label">Valve</div><div class="nav-sub">Annonces officielles</div></div>
    </div>
    <div class="nav-item" onclick="location.href='/FasiChatClassroom/public/logout'">
      <div class="nav-icon" style="background:rgba(239,68,68,0.12);">🚪</div>
      <div><div class="nav-label">Déconnexion</div><div class="nav-sub">Quitter</div></div>
    </div>
  </div>
  <div class="sidebar-profile">
    <div class="profile-avatar" style="background:linear-gradient(135deg,#dc2626,#991b1b);"><div class="online-dot"></div>📜</div>
    <div class="profile-info"><h4><?= htmlspecialchars($currentUser['prenom'] . ' ' . $currentUser['nom']) ?></h4><span>Doyen · Faculté</span></div>
  </div>
</div>
<div class="main-area">
  <div class="admin-topbar">
    <div class="admin-topbar-info">
      <h2>Bienvenue, <?= htmlspecialchars($currentUser['prenom']) ?> 👋</h2>
      <p>Tableau de bord administratif — Faculté des Sciences Informatiques</p>
    </div>
    <div class="admin-topbar-right">
      <div class="current-time" id="currentTime"></div>
    </div>
  </div>
  <div class="stats-grid">
    <div class="stat-card blue">
      <div class="stat-icon">👨‍🎓</div>
      <div class="stat-content">
        <div class="stat-number"><?= $totalEtud ?></div>
        <div class="stat-label">Étudiants</div>
      </div>
    </div>
    <div class="stat-card green">
      <div class="stat-icon">👨‍🏫</div>
      <div class="stat-content">
        <div class="stat-number"><?= $totalEns ?></div>
        <div class="stat-label">Personnel (Ens. + Ass.)</div>
      </div>
    </div>
    <div class="stat-card purple">
      <div class="stat-icon">📚</div>
      <div class="stat-content">
        <div class="stat-number"><?= $totalCours ?></div>
        <div class="stat-label">Cours</div>
      </div>
    </div>
    <div class="stat-card orange">
      <div class="stat-icon">👥</div>
      <div class="stat-content">
        <div class="stat-number"><?= $totalUsers ?></div>
        <div class="stat-label">Total utilisateurs</div>
      </div>
    </div>
  </div>
  <div class="content-area">
    <p style="color:#9ca3af;text-align:center;padding:40px;">Tableau de bord en cours de développement. Utilisez la Valve pour les annonces.</p>
  </div>
</div>
<script>
function updateClock() {
    const now = new Date();
    document.getElementById('currentTime').textContent = now.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}
updateClock();
setInterval(updateClock, 60000);
</script>
</body>
</html>
