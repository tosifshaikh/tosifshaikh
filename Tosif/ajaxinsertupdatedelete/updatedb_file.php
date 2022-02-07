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

if($action == "update" && $emp_id > 0){

	$query_customers = "Update `employee` set `emp_fname` = '" .$fname. "',`emp_mname` = '" .$mname. "',`emp_lname` = '" .$lname. "',`emp_email` = '" .$email. "',`emp_contact` = '" .$contact. "',`emp_designation` = '" .$designation. "',`emp_gender` = '" .$gender. "',`emp_country` = '" .$country. "',`emp_comments`='" .$comments. "'Where `employee_id`='".$emp_id."' ";
	
	$sql_customers = mysql_query($query_customers) or die(mysql_error());
	
	
	
	if(mysql_affected_rows() > 0){
	echo $emp_id;
	}else{
	echo 0;
	}

}///Update




?>