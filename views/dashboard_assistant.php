<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'assistant') {
    header('Location: /FasiChatClassroom/public/login');
    exit();
}
$currentUser = $_SESSION['user'];
require_once __DIR__ . '/../src/Autoloader.php';
require_once __DIR__ . '/../database/Database.php';
$dbInstance = new Database();
$db = $dbInstance->getConnection();
$stmtEtud = $db->query("SELECT * FROM utilisateurs WHERE role = 'etudiant'");
$etudiants = $stmtEtud->fetchAll();
$stmtColl = $db->prepare("SELECT * FROM utilisateurs WHERE role IN ('enseignant', 'assistant') AND id != :my_id");
$stmtColl->execute(['my_id' => $currentUser['id']]);
$collegues = $stmtColl->fetchAll();
$convocModel = new \Models\Convocation($db);
$convocations = $convocModel->recupererToutes();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FasiChat — Dashboard Assistant</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/FasiChatClassroom/public/assets/css/dashboard_enseignant.css">
</head>
<body>
<div class="sidebar">
  <div class="sidebar-header">
    <div class="brand-mark">💬</div>
    <div class="brand-info"><h3>FasiChat</h3><span>Espace Assistant</span></div>
  </div>
  <div class="nav-tabs">
    <button class="nav-tab active" onclick="showView('students',this)">👥 Étudiants</button>
    <button class="nav-tab" onclick="showView('mur',this)">📋 Mur</button>
    <button class="nav-tab" onclick="showView('msgs',this)">💬 Messages</button>
    <button class="nav-tab" onclick="location.href='/FasiChatClassroom/public/valve'">📣 Valve</button>
  </div>
  <div class="sidebar-search">
    <div class="search-wrap">
      <span class="search-icon">🔍</span>
      <input type="text" class="search-input" placeholder="Rechercher...">
    </div>
  </div>
  <div class="conv-list">
    <div class="section-label">Mes Cours</div>
    <div class="conv-item active" onclick="selectConv(this,'PHP POO — L2 FASI','🖥','linear-gradient(135deg,#3b82f6,#1d4ed8)','Cours Public','public', 1)">
      <div class="avatar" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);">🖥</div>
      <div class="conv-info"><div class="conv-name">PHP POO — L2 FASI</div><div class="conv-preview">Cours Public</div></div>
      <div class="conv-meta"><div class="conv-time">Actif</div><div class="conv-badge">3</div></div>
    </div>
    <div class="section-label">Collègues (Privé)</div>
    <?php foreach ($collegues as $col): ?>
    <div class="conv-item" onclick="selectConv(this,'<?= htmlspecialchars($col['prenom'].' '.$col['nom'], ENT_QUOTES) ?>','👤','linear-gradient(135deg,#6366f1,#4f46e5)','<?= ucfirst($col['role']) ?>','prive', <?= $col['id'] ?>)">
      <div class="avatar" style="background:linear-gradient(135deg,#6366f1,#4f46e5);font-size:12px;font-weight:700;"><?= strtoupper(substr($col['prenom'],0,1).substr($col['nom'],0,1)) ?></div>
      <div class="conv-info"><div class="conv-name"><?= htmlspecialchars($col['prenom'].' '.$col['nom']) ?></div><div class="conv-preview">Chat privé</div></div>
    </div>
    <?php endforeach; ?>
    <div class="section-label">Convocations reçues</div>
    <?php if (empty($convocations)): ?>
        <div style="padding:10px;font-size:12px;color:#9ca3af;text-align:center;">Aucune convocation</div>
    <?php else: ?>
        <?php foreach ($convocations as $conv): ?>
        <div class="conv-item" onclick="alert('Détails : <?= htmlspecialchars($conv['objet']) ?>\nLieu : <?= htmlspecialchars($conv['lieu']) ?>\nDate : <?= htmlspecialchars(($conv['date_convocation'] ?? '') . ' ' . ($conv['heure_convocation'] ?? '')) ?>')">
          <div class="avatar" style="background:linear-gradient(135deg,#dc2626,#991b1b);">🏛</div>
          <div class="conv-info">
            <div class="conv-name"><?= htmlspecialchars($conv['expediteur_prenom'] . ' ' . $conv['expediteur_nom']) ?></div>
            <div class="conv-preview"><?= htmlspecialchars(substr($conv['objet'], 0, 30)) ?>...</div>
          </div>
          <div class="conv-meta">
            <div class="conv-time"><?= date('H:i', strtotime($conv['date_convocation'] ?? '')) ?></div>
            <div class="conv-badge-warn">!</div>
          </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
  </div>
  <div class="sidebar-profile">
    <div class="profile-avatar"><div class="online-dot"></div>👨‍🏫</div>
    <div class="profile-info"><h4><?= htmlspecialchars($currentUser['prenom'] . ' ' . $currentUser['nom']) ?></h4><span>Assistant</span></div>
    <div class="profile-actions"><a href="/FasiChatClassroom/public/login" class="icon-btn">🚪</a></div>
  </div>
</div>
<div class="main-area">
  <div class="chat-topbar">
    <div class="chat-topbar-avatar" id="topbarAvatar" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);">🖥</div>
    <div class="chat-topbar-info">
      <h3 id="topbarTitle">PHP POO — L2 FASI</h3>
      <p id="topbarSub">Chargement...</p>
    </div>
    <div class="status-badge public"><div class="status-dot"></div>Cours Public</div>
    <div class="topbar-actions">
      <button class="topbar-btn" onclick="showView('students',null)">👥 Étudiants</button>
      <button class="topbar-btn" onclick="showView('mur',null)">📋 Mur péda.</button>
      <button class="topbar-btn primary" onclick="showView('msgs',null)">💬 Messagerie</button>
    </div>
  </div>
  <div class="students-panel visible" id="view-students">
    <div class="panel-header">
      <div><h2>Liste des étudiants</h2><p>Chargement...</p></div>
    </div>
    <div class="search-students">
      <div class="search-field">
        <span class="s-icon">🔍</span>
        <input type="text" placeholder="Rechercher un étudiant...">
      </div>
      <button class="filter-btn active">Tous</button>
      <button class="filter-btn">En ligne</button>
      <button class="filter-btn">Hors ligne</button>
    </div>
    <div class="students-grid">
      <div class="student-card">
        <div class="sc-header">
          <div class="sc-avatar" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);">ET</div>
          <div><div class="sc-name">Étudiant</div></div>
        </div>
        <div class="sc-actions">
          <button class="sc-btn msg" onclick="selectConv(null, 'Étudiant','👤','linear-gradient(135deg,#3b82f6,#1d4ed8)','Étudiant','prive', 1); showView('msgs', document.querySelectorAll('.nav-tab')[2])">💬 Message</button>
        </div>
      </div>
    </div>
  </div>
  <div class="mur-panel" id="view-mur">
    <div class="mur-compose">
      <h3>📋 Publier sur le mur pédagogique</h3>
      <textarea class="mur-textarea" placeholder="Rédigez une question, un rappel ou une annonce..."></textarea>
      <div class="mur-footer">
        <div class="mur-attach">
          <button class="attach-btn">📎 Fichier</button>
          <button class="attach-btn">🔗 Lien</button>
        </div>
        <button class="publish-btn" onclick="publishPost()">Publier →</button>
      </div>
    </div>
    <div id="mur-posts">
    </div>
  </div>
  <div class="chat-messages" id="view-msgs">
    <div class="date-sep">Chargement des messages...</div>
  </div>
  <div class="chat-input-area" id="input-area" style="display:none;">
    <div class="input-row">
      <div class="msg-textarea-wrap">
        <textarea class="msg-textarea" placeholder="Répondre..." rows="1" id="msgInput"></textarea>
      </div>
      <button class="attach-btn" onclick="triggerUpload()">📎</button>
      <input type="file" id="fileInput" style="display:none;" onchange="handleFileUpload(this)">
      <button class="send-btn" onclick="sendMsg()">➤</button>
    </div>
  </div>
</div>
<div class="right-panel">
  <div class="panel-section">
    <div class="panel-title">Statistiques</div>
    <div class="info-card">
      <h4>PHP POO — L2 FASI</h4>
      <div class="stat-row">
        <div class="stat-box"><div class="num">0</div><div class="lbl">Étudiants</div></div>
        <div class="stat-box"><div class="num">0</div><div class="lbl">En ligne</div></div>
        <div class="stat-box"><div class="num">0</div><div class="lbl">Messages</div></div>
      </div>
    </div>
  </div>
</div>
<script src="/FasiChatClassroom/public/assets/js/dashboard_enseignant.js"></script>
<script>initChat(<?= $currentUser['id'] ?>, 1);</script>
</body>
</html>
