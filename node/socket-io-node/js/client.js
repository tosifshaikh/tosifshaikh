const socket = io('http://localhost:8890', {
    reconnectionDelayMax: 10000,
    auth: {
      token: "123"
    },
    query: {
      "my-key": "my-value"
    }
});

const form = document.getElementById('send-container');
const messageInput = document.getElementById('messageText');
const msgContainer = document.querySelector('.container');
var audio = new Audio('chat.mp3');

//this will append the messages in to div.
const append = (message, position) => {
        const  msgElement = document.createElement('div');
        msgElement.innerText = message;
        msgElement.classList.add('message');
        msgElement.classList.add(position);
        msgContainer.appendChild(msgElement);
    if (position == 'left') {
         audio.play();
    }
   
}
//listens the form submit and emmits the event send to server.
form.addEventListener('submit', (e) => {
    e.preventDefault();
    const message = messageInput.value;
    append(`You : ${message}`, 'right');
    socket.emit('send', message);
    messageInput.value = '';
});
//asks user to enter a name when join
let userName = prompt('Enter your name to join');

//Emmit's the new-user-joined event to server.
socket.emit('new-user-joined', userName);

//Receives the event from server 'user-joined' 
socket.on('user-joined', name => {
    append(`${name} has joined the chat.`,'right');
}); 

//receive event
socket.on('receive', data => {
    append(`${data.name}  : ${data.message}`, 'left' );
});

//when user leave the chat
socket.on('left', name => {
    append(`${name} has left the chat.`, 'right' );
});