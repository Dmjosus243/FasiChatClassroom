// Variables globales pour suivre la discussion en cours
let currentChatType = 'public'; // 'prive', 'public', 'mur'
let currentChatId = 1;         // ID de la cible (ID utilisateur, ID cours, ou ID promotion)
let currentChatName = 'PHP POO — Promo L2';
let currentUserId = null;

// Définir l'ID de l'utilisateur connecté (passé par PHP)
function initChat(userId, promoId) {
    currentUserId = userId;
    currentChatId = promoId; // Par défaut, on ouvre le chat public de la promotion
    
    // Démarrer le polling (rafraîchissement automatique toutes les 3 secondes)
    loadMessages();
    setInterval(loadMessages, 3000);
}

// Changer de discussion lors du clic sur un élément de la liste
function changerDiscussion(element, type, id, name) {
    document.querySelectorAll('.conv-item').forEach(i => i.classList.remove('active'));
    element.classList.add('active');
    
    currentChatType = type;
    currentChatId = id;
    currentChatName = name;
    
    // Mettre à jour l'en-tête de la messagerie
    document.getElementById('topbarTitle').textContent = name;
    
    const badge = document.getElementById('statusBadge');
    if (type === 'prive') {
        badge.textContent = 'Message Privé';
        badge.className = 'status-badge private';
    } else if (type === 'public') {
        badge.textContent = 'Message Public';
        badge.className = 'status-badge public';
    } else {
        badge.textContent = 'Mur Pédagogique';
        badge.className = 'status-badge mur';
    }
    
    // Charger immédiatement les messages de la nouvelle conversation
    loadMessages();
}

// Gérer l'appui sur Entrée
function handleKey(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMsg();
    }
}

// Envoyer un message au serveur (AJAX)
function sendMsg() {
    const ta = document.getElementById('msgInput');
    const text = ta.value.trim();
    if (!text) return;
    
    // Préparer les données à envoyer
    const formData = new FormData();
    formData.append('contenu', text);
    formData.append('type', currentChatType);
    
    if (currentChatType === 'prive') {
        formData.append('destinataire_id', currentChatId);
    } else if (currentChatType === 'public') {
        formData.append('promotion_id', currentChatId);
    } else if (currentChatType === 'mur') {
        formData.append('cours_id', currentChatId);
    }
    
    // Envoi asynchrone
    fetch('/FasiChatClassroom/public/message-send', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            ta.value = '';
            ta.style.height = 'auto';
            loadMessages(); // Recharger immédiatement pour voir notre message
        } else {
            alert('Erreur lors de l\'envoi du message.');
        }
    })
    .catch(err => console.error('Erreur:', err));
}

// Charger les messages du serveur (AJAX Polling)
function loadMessages() {
    if (!currentChatId) return;
    
    fetch(`/FasiChatClassroom/public/message-poll?type=${currentChatType}&id=${currentChatId}`)
    .then(response => response.json())
    .then(messages => {
        const container = document.getElementById('messages');
        container.innerHTML = ''; // On vide pour réécrire proprement
        
        if (messages.length === 0) {
            container.innerHTML = '<div class="date-sep">Aucun message pour l\'instant. Lancez la conversation !</div>';
            return;
        }
        
        let derniereDate = '';
        
        messages.forEach(msg => {
            // Afficher le séparateur de date simple
            const dateMessage = new Date(msg.date_envoi).toLocaleDateString();
            if (dateMessage !== derniereDate) {
                derniereDate = dateMessage;
                const sep = document.createElement('div');
                sep.className = 'date-sep';
                sep.textContent = dateMessage;
                container.appendChild(sep);
            }
            
            const estLeMien = (parseInt(msg.expediteur_id) === parseInt(currentUserId));
            const row = document.createElement('div');
            row.className = estLeMien ? 'msg-row mine' : 'msg-row';
            
            // Initiales pour l'avatar
            const initiales = (msg.prenom.charAt(0) + msg.nom.charAt(0)).toUpperCase();
            
            const heure = new Date(msg.date_envoi).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            row.innerHTML = `
                <div class="msg-avatar" style="${estLeMien ? 'background: linear-gradient(135deg, var(--sky), var(--accent));' : 'background: linear-gradient(135deg, #0ea5e9, #0284c7);'}">
                    ${initiales}
                </div>
                <div class="msg-group">
                    <div class="msg-sender">${msg.prenom} ${msg.nom} · ${heure}</div>
                    <div class="bubble ${estLeMien ? 'mine' : 'theirs'}">${escapeHTML(msg.contenu)}</div>
                    <div class="msg-meta">${heure} ${estLeMien ? '<span class="check-read">✓✓</span>' : ''}</div>
                </div>
            `;
            container.appendChild(row);
        });
        
        // Faire défiler automatiquement vers le bas
        container.scrollTop = container.scrollHeight;
    })
    .catch(err => console.error('Erreur chargement messages:', err));
}

// Fonction utilitaire pour éviter les failles XSS
function escapeHTML(str) {
    return str.replace(/[&<>'"]/g, 
        tag => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            "'": '&#39;',
            '"': '&quot;'
        }[tag] || tag)
    );
}

// Autoresize textarea de saisie
document.getElementById('msgInput').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
});
