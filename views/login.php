<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FasiChat Classroom — Connexion</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
<div class="bg-layer"></div>
<div class="grid-lines"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<div class="login-wrapper">
  <!-- Left Panel -->
  <div class="left-panel">
    <div class="brand">
      <div class="brand-logo">💬</div>
      <div class="brand-name">Fasi<span>Chat</span></div>
      <div class="brand-sub">Classroom Edition &mdash; Plateforme Académique</div>
    </div>

    <div class="features">
      <div class="feature-item">
        <div class="feature-icon">📚</div>
        <div class="feature-text">
          <h4>Cours & Promotions</h4>
          <p>Regroupez étudiants et enseignants par cours et promotion</p>
        </div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">🔒</div>
        <div class="feature-text">
          <h4>Messagerie Sécurisée</h4>
          <p>Messages privés, publics et mur pédagogique selon les rôles</p>
        </div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">📁</div>
        <div class="feature-text">
          <h4>Partage de Fichiers</h4>
          <p>PDF, vidéos, documents jusqu'à 20 Mo avec compression auto</p>
        </div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">📣</div>
        <div class="feature-text">
          <h4>Onglet Valve</h4>
          <p>Annonces institutionnelles visibles par toute la communauté</p>
        </div>
      </div>
    </div>

    <div class="left-bottom">© 2026 FasiChat Classroom. Tous droits réservés.</div>
  </div>

  <!-- Right Panel -->
  <div class="right-panel">
    <div class="form-header">
      <h2>Bienvenue FreeDom</h2>
      <p>Connectez-vous à votre espace académique</p>
    </div>

    <?php if (isset($_GET['error'])): ?>
      <div style="color: #ef4444; background: rgba(239, 68, 68, 0.1); padding: 10px; border-radius: 8px; margin-bottom: 16px; text-align: center; font-size: 13px; font-weight: 600; border: 1px solid rgba(239, 68, 68, 0.2); font-family: 'Sora', sans-serif;">
        ⚠️ Identifiants ou rôle incorrects.
      </div>
    <?php endif; ?>

    <div class="role-selector">
      <button class="role-btn active" onclick="setRole(this)">Étudiant</button>
      <button class="role-btn" onclick="setRole(this)">Enseignant</button>
      <button class="role-btn" onclick="setRole(this)">Assistant</button>
    </div>

    <form action="/FasiChatClassroom/public/login-handler" method="post">
      <!-- Rôle sélectionné caché -->
      <input type="hidden" name="role" id="role-input" value="étudiant">

      <div class="form-group">
        <label class="form-label">Identifiant / Matricule</label>
        <div class="input-wrapper">
          <span class="input-icon">👤</span>
          <input type="text" name="email" class="form-input" placeholder="Ex: ET2024001" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Mot de passe</label>
        <div class="input-wrapper">
          <span class="input-icon">🔑</span>
          <input type="password" name="password" class="form-input" placeholder="••••••••" required>
        </div>
      </div>

      <div class="form-row">
        <label class="checkbox-wrap">
          <input type="checkbox">
          <span class="custom-check">✓</span>
          <span class="checkbox-label">Se souvenir de moi</span>
        </label>
        <a href="#" class="forgot-link">Mot de passe oublié ?</a>
      </div>

      <button type="submit" class="btn-login">Se connecter →</button>
    </form>

<script src="/FasiChatClassroom/public/assets/js/login.js"></script>
</body>
</html>
