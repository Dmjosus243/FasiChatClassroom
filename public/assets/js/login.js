function setRole(btn) {
  document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  const role = btn.textContent.trim().toLowerCase();
  
  // On met à jour la valeur du rôle dans le champ caché
  const roleInput = document.getElementById('role-input');
  if (roleInput) {
    roleInput.value = role;
  }
}

// Checkbox toggle
document.querySelectorAll('.checkbox-wrap').forEach(wrap => {
  wrap.addEventListener('click', (e) => {
    // Empêche le comportement par défaut si on clique sur le label
    e.preventDefault();
    const cb = wrap.querySelector('input');
    cb.checked = !cb.checked;
  });
});
