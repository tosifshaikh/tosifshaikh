
<?php
	session_start();
	
	$username=$_POST['untxt'];
		$password=$_POST['pstxt'];
		
	if(!isset($_POST['submit']))
	{
		$conn=mysqli_connect("192.168.100.22","root","admin","tosif");
		
		
		
		if(!empty($username) && !empty($password))
		{
			$query="select * from student where username='$username' and password='$password'";
			$result=mysqli_query($conn,$query);
			if(mysqli_num_rows($result)==1)
			{
				echo "u got jackpot...";
			}
			else
			{
				echo "first buy ticket.."	;
			}
		}
		else
		{
			echo "Please enter un & ps";
		}
	}
?>
<form action="login.php" method="post">
<input type="submit" id="lg" value="logout"/>
</form>