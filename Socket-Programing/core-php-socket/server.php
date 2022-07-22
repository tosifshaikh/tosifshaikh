<?php
$host = '127.0.0.1';
$port = 80881;
//Creates and returns a Socket instance.
//AF_INET - IPv4 Internet based protocols, SOCK_STREAM - full-duplex
$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die('Socket not created');

//Binds a name to a socket
socket_bind($socket, $host, $port) or die('Socket not bind');

//Listens for a connection on a socket
socket_listen($socket, 3) or die('Socket not listen');

//loop for continous listen otherwise, it will stop after one listen
do {
    //Accepts a connection on a socket
    $accept = socket_accept($socket) or die('Socket not accept');

    //Reads a maximum of length bytes from a socket
    $msg = socket_read($accept, 1024);

    $msg = trim($msg);
    echo $msg . "\n";

    //reply to user through cmd
    echo 'Enter Reply:';
    $reply = fgets(STDIN);
    socket_write($accept, $reply, strlen($reply)) or die('unable to output');
} while (true);

socket_close($socket);
socket_close($accept);
