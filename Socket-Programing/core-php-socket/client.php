<?php
if (isset($_REQUEST['submit']) && !empty($_REQUEST['msg'])) {
    $host = '127.0.0.1';
    $port = 80881;
    $socket = socket_create(AF_INET, SOCK_STREAM, 0) or die('Socket not created');

    // Initiates a connection on a socket
    socket_connect($socket, $host, $port) or die('Socket not connected');

    $msg = trim($_REQUEST['msg']);
    //Write to a socket
    socket_write($socket, $msg, strlen($msg)) or die('unable to output');

    //print message send by server
    $reply = socket_read($socket, 1024);
    $reply = trim($reply);
    echo $reply;
    socket_close($socket);
}

?>
<form method="POST">
    <input type="text" name="msg">
    <input type="submit" value="submit" name="submit">
</form>