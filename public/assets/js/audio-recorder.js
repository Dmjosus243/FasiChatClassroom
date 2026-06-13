class AudioRecorder {
    constructor() {
        this.mediaRecorder = null;
        this.audioChunks = [];
        this.isRecording = false;
        this.startTime = null;
    }

    async start() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            this.mediaRecorder = new MediaRecorder(stream);
            this.audioChunks = [];
            
            this.mediaRecorder.ondataavailable = (event) => {
                if (event.data.size > 0) this.audioChunks.push(event.data);
            };
            
            this.mediaRecorder.onstop = () => {
                const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                const duration = Math.floor((Date.now() - this.startTime) / 1000);
                if (this.onStopCallback) this.onStopCallback(audioBlob, duration);
                stream.getTracks().forEach(track => track.stop());
            };
            
            this.mediaRecorder.start(100);
            this.isRecording = true;
            this.startTime = Date.now();
            return true;
        } catch (error) {
            console.error('Erreur microphone:', error);
            return false;
        }
    }

    stop() {
        if (this.mediaRecorder && this.isRecording) {
            this.mediaRecorder.stop();
            this.isRecording = false;
        }
    }

    onStop(callback) {
        this.onStopCallback = callback;
    }
}

window.audioRecorder = new AudioRecorder();

async function sendAudioMessage(destinataireId, audioBlob, duration) {
    const formData = new FormData();
    formData.append('destinataire_id', destinataireId);
    formData.append('contenu', 'Message audio');
    formData.append('duree', duration);
    formData.append('audio', audioBlob, 'audio.webm');
    
    const csrfToken = document.querySelector('input[name="csrf_token"]')?.value || '';
    formData.append('csrf_token', csrfToken);
    
    const response = await fetch('/FasiChatClassroom/public/message/audio', { method: 'POST', body: formData });
    return await response.json();
}