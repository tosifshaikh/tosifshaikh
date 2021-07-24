<?php

session_start();
require_once('connection.php');
$email=$_POST['txtEmail'];
$pass=$_POST['txtPass'];
$nm=$_POST['txtFname']." ".$_POST['txtMname']." ".$_POST['txtLname'];

$address=$_POST['txtAddress'];
$gender=$_POST['genderGroup'];
if($gender=="Male")
{
	$gender=str_replace("Male","M",$gender);
}
else
{
	$gender=str_replace("Female","F",$gender);
}
$selectCountry=$_POST['selectCountry'];
$hobbiesGroup=implode(",",$_POST['hobbiesGroup']);
$fp = fopen($_FILES['myFile']['tmp_name'], "r");
	
// If successful, read from the file pointer using the size of the file (in bytes) as the length.	 
if ($fp) {
     $content = fread($fp, $_FILES['myFile']['name']);
     fclose($fp);
	
     // Add slashes to the content so that it will escape special characters.
     // As pointed out, mysql_real_escape_string can be used here as well. Your choice.		 
     $content = addslashes($content);
		
     // Insert into the table "table" for column "image" with our binary string of data ("content")	 
    // mysql_query("Insert into table (image) Values('$content')");
}


//$myFile=$_FILES['myFile']['name'];
if(isset($_POST["txtSubmit"]))
{
	//move_uploaded_file($_FILES['myFile']['tmp_name'],"images/".$_FILES['myFile']['name']);
	$ins="insert into tbl_login(email,pass,name,address,gender,country,hobbies,img) values('$email','$pass','$nm','$address','$gender','$selectCountry','$hobbiesGroup','$content')";
	
	$result=mysqli_query($conn,$ins) or die(mysqli_error($conn));
	if(isset($result))
	{
		echo "data inserted successfully";
		header("Location:login.php");
	}
}
session_destroy();
?>