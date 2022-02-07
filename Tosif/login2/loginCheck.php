<?php

	session_start();
	require_once('connection.php');
	if(isset($_POST['btnLogin']))
	{	
	    $myUn=$_POST['txtUser'];
	    $myPass=$_POST['txtPass'];	
		$result=mysqli_query($conn,"select email,pass from tbl_login where email='$myUn' and pass='$myPass'");
		
		 $rowcount=mysqli_num_rows($result);
	   if($rowcount>0)
		{
			if(isset($_POST['chCookie']))
			{
				setcookie('datacookie',$myUn);
			}
			while($row = mysqli_fetch_assoc($result))
			{
				if($row["email"]==$myUn && $row["pass"]==$myPass)
				{
					//$GLOBALS['msg']=$myUn;
					header("location:profile.php?email='".$row["email"]."'");
					//echo "wel come to StarBook...".$myUn;
				}
				
			}
		}
		else
		{	
			header("location:index.php?msg='Invalid User or Password'");
			
		}
	 }
	//if(isset($_POST['btnLogOut']))
//	{
//		header('location:login.php');
//	}
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<form method="post">
<input type="submit" name="btnLogOut" value="LogOut" />
</form>
</body>
</html>
