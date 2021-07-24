
<?php
session_start();
include('conn.php');

$nam=$_REQUEST['name'];
$pass=$_REQUEST['pass'];

if (!$conn) {
   die('Could not connect: ' . mysql_error());
}

 $result = mysqli_query($conn,"SELECT * FROM member WHERE username='$nam' and password='$pass' and flg=1");
 $rowcount=mysqli_num_rows($result);
 if($rowcount>0)
 {
	 $_SESSION['uname']=$nam;
	header('location:userDetail.php?uname='.$nam.'');
 }
 else
 {
	 header("location:form.php?msg='invalid user'");
 }
if($nam=='admin' && $pass=='admin')
{
	$_SESSION['admin']='ADMIN';
	header("location:Search.php");
}

?>