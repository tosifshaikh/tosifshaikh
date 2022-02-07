<?php
//echo $_SERVER['REMOTE_ADDR'];
//echo $_SERVER['PHP_SELF'];
//$ip=$_SERVER['REMOTE_ADDR'];
$ip="192.168.1.3";
echo "IP address= $ip"."<br/>";
if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
    echo("$ip is a valid IPv6 address");
} else {
    echo("$ip is not a valid IPv6 address");
}
?>