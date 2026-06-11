// Variables globales
let currentChatType = 'public';
let currentChatId = 1;
let currentUserId = null;
let selectedFileId = null;

// Initialisation (appelée depuis le PHP)
window.initChat = function(userId, defaultPromoId) {
    currentUserId = userId;
    currentChatId = defaultPromoId;
    loadMessages();
    setInterval(loadMessages, 3000);
};

window.showView = function(view, btn) {
    document.getElementById('view-students').classList.remove('visible');
    document.getElementById('view-mur').classList.remove('visible');
    document.getElementById('view-msgs').classList.remove('visible');
    document.getElementById('input-area').style.display = 'none';
    if (view === 'students') document.getElementById('view-students').classList.add('visible');
    else if (view === 'mur') document.getElementById('view-mur').classList.add('visible');
    else if (view === 'msgs') {
        document.getElementById('view-msgs').classList.add('visible');
        document.getElementById('input-area').style.display = 'block';
    }
    if (btn) { document.querySelectorAll('.nav-tab').forEach(b => b.classList.remove('active')); btn.classList.add('active'); }
};

window.selectConv = function(item, title, icon, bg, sub, type, id) {
    if (item) {
        document.querySelectorAll('.conv-item').forEach(i => i.classList.remove('active'));
        item.classList.add('active');
    }
    currentChatType = type;
    currentChatId = id;
    document.getElementById('topbarTitle').textContent = title;
    document.getElementById('topbarSub').textContent = sub;
    loadMessages();
};

window.handleKey = function(e) { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); window.sendMsg(); } };

window.sendMsg = function() {
    const ta = document.getElementById('msgInput');
    const text = ta.value.trim();
    if (!text && !selectedFileId) return;

    const formData = new FormData();
    formData.append('contenu', text);
    formData.append('type', currentChatType);
    if (selectedFileId) formData.append('fichier_id', selectedFileId);

    if (currentChatType === 'prive') formData.append('destinataire_id', currentChatId);
    else if (currentChatType === 'public') formData.append('promotion_id', currentChatId);
    else if (currentChatType === 'mur') formData.append('cours_id', currentChatId);

    fetch('/FasiChatClassroom/public/message-send', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            ta.value = '';
            selectedFileId = null;
            loadMessages();
        } else {
            alert('Erreur: ' + (data.error || 'Échec de l\'envoi'));
        }
    });
};

function loadMessages() {
    if (!currentChatId) return;
    fetch(`/FasiChatClassroom/public/message-poll?type=${currentChatType}&id=${currentChatId}`)
    .then(res => res.json())
    .then(messages => {
        const container = document.getElementById('view-msgs');
        container.innerHTML = '';
        if (messages.length === 0) {
            container.innerHTML = '<div class="date-sep">Aucun message.</div>';
            return;
        }
        
        messages.forEach(msg => {
            const estLeMien = (parseInt(msg.expediteur_id) === parseInt(currentUserId));
            const row = document.createElement('div');
            row.className = estLeMien ? 'msg-row mine' : 'msg-row';
            const heure = new Date(msg.date_envoi).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            let fileHtml = '';
            if (msg.fichier_id && msg.nom_stockage) {
                fileHtml = `<div class="file-bubble">
                                <div class="file-icon">📎</div>
                                <div class="file-info">Fichier Joint</div>
                                <a href="/FasiChatClassroom/public/assets/uploads/${msg.nom_stockage}" target="_blank">⬇</a>
                            </div>`;
            }

            row.innerHTML = `
                <div class="msg-avatar" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    ${(msg.prenom.charAt(0) + msg.nom.charAt(0)).toUpperCase()}
                </div>
                <div class="msg-group">
                    <div class="msg-sender">${msg.prenom} ${msg.nom} · ${heure}</div>
                    ${fileHtml}
                    <div class="bubble ${estLeMien ? 'mine' : 'theirs'}">${escapeHTML(msg.contenu)}</div>
                </div>`;
            container.appendChild(row);
        });
        container.scrollTop = container.scrollHeight;
    });
}

function escapeHTML(str) {
    return str.replace(/[&<>'"]/g, tag => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'':'&#39;','"':'&quot;'}[tag] || tag));
}

window.triggerUpload = function() {
    document.getElementById('fileInput').click();
};

window.handleFileUpload = function(input) {
    if (input.files && input.files[0]) {
        const formData = new FormData();
        formData.append('file', input.files[0]);
        fetch('/FasiChatClassroom/public/file-upload', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                selectedFileId = data.file_id;
                alert('Fichier prêt pour l\'envoi !');
            }
        });
    }
};

document.getElementById('msgInput').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
});
