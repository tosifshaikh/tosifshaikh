<?php 
$arr = array(3,5,8);

if(isset($_REQUEST["Username"]))
$arr[3]=$_REQUEST["Username"];

echo json_encode($arr);
 ?>