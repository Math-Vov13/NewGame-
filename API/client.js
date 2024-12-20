const socket = new WebSocket('ws://localhost:8080');

socket.onopen = function() {
    console.log('Connecté au serveur WebSocket');
};

socket.onmessage = function(event) {
    console.log('Message reçu:', event.data);
};

// Envoi de message
socket.send('Hello WebSocket');