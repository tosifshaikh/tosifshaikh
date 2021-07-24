<?php
include("sql_connection.php");

if(!empty($_POST["fname"])){
$fname = $_POST["fname"];
}

if(!empty($_POST["mname"])){
$mname = $_POST["mname"];
}

if(!empty($_POST["lname"])){
$lname = $_POST["lname"];
}

if(!empty($_POST["email"])){
$email = $_POST["email"];
}

if(!empty($_POST["contact"])){
$contact = $_POST["contact"];
}

if(!empty($_POST["designation"])){
$designation = $_POST["designation"];
}

if(!empty($_POST["gender"])){
$gender = $_POST["gender"];
}

if(!empty($_POST["country"])){
$country = $_POST["country"];
}

if(!empty($_POST["comments"])){
$comments = $_POST["comments"];
}

if(!empty($_POST["action"])){
$action = $_POST["action"];
}


$emp_id = 0;

if(!empty($_POST["emp_id"])){
$emp_id = $_POST["emp_id"];
}

if($action == "insert" && $emp_id == 0){

	$query_customers = "INSERT INTO `employee` (`emp_fname`,`emp_mname`,`emp_lname`,`emp_email`,`emp_contact`,`emp_designation`,`emp_gender`,`emp_country`,`emp_comments`) VALUES ('" .$fname. "','" .$mname. "','" .$lname. "','" .$email. "','" .$contact. "','" .$designation. "','" .$gender. "','" .$country. "','" .$comments. "')";
	
	$sql_customers = mysql_query($query_customers) or die(mysql_error());
	
	$InsertID = mysql_insert_id();
	
	if(mysql_affected_rows() > 0){
	echo $InsertID;
	}else{
	echo 0;
	}

}///Insert




?>