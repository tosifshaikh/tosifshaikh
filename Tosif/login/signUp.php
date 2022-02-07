<?php
include 'conn.php';
 $uname=$_POST['uname'];
 $pass=$_POST['txtpass'];
 $pass2=$_POST['txtpass2'];

 $target='profile pic/';
 $file=$_FILES['profile']['name'];
$target_file = $target. basename($_FILES["profile"]["name"]);

echo $target_file;
 if($pass==$pass2)
 {

	 move_uploaded_file($_FILES['profile']['tmp_name'],$target_file);
	 $sql=mysqli_query($con,"INSERT INTO student(username,password,prof) VALUES ('$uname','$pass','$target_file')");
	 header('location:login.php');
 }
 else
 {
 	echo 'enter same password';
 }
 ?>