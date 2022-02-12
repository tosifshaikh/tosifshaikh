const io = require('socket.io')(8890, {
    cors : {
        origin : ['http://localhost']
    }
});
const user = {};
 //initiate the connection
io.on('connection', (socket) => {
    //receives an event from client 'new-user-joined'
    socket.on('new-user-joined',name => {
        user[socket.id] = name;
        //send msg to all group accept the sender
        //requires atleast 2 peple to broadcast the message.
        socket.broadcast.emit('user-joined', name);
    });
    //receives an event 'send'
    socket.on('send',message => {
        socket.broadcast.emit('receive',{message : message, name : user[socket.id]});
    });
    socket.on('disconnect', message => {
        //broadcast everyone that user has left the chat
        socket.broadcast.emit('left', user[socket.id]);
        delete user[socket.id];
    });

});
