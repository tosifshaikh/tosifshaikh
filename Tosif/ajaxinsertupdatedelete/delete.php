<?php
include("sql_connection.php");

if(!empty($_GET["empid"])){
$empid = $_GET["empid"];
}

	
	
	$Q_select = "DELETE from `employee` Where `employee_id`='".$empid."'";
	$sql_Q = mysql_query($Q_select) or die(mysql_error());	
	
	
	if(mysql_affected_rows() > 0){
	echo $empid;
	}else{
	echo 0;
	}
	
	
?>
