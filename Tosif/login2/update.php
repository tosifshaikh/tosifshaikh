<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
		require_once('connection.php');
		$result=mysqli_query($conn,"select * from tbl_login where id=".$_GET["id"]."");
		
		 $rowcount=mysqli_num_rows($result);
	   if($rowcount>0)
		{
			
			while($row = mysqli_fetch_assoc($result))
			{
				
				
					echo "<table align='center' border='5'>";
					echo "<form method='post' >";
					echo "<tr><td><h1>wel come to StarBook...</h1></td></tr>";
					echo "<tr><td><input type='submit' name='Update' value='Update' /></td></tr>";
					
					echo "<tr><td>Name: </td><td><input type='text' name='name' value=".$row["name"]."></td></tr>";
					echo "<tr><td>Email: </td><td><input type='text' name='email' value=".$row["email"]."></td></tr>";
					echo "<tr><td>Address: </td><td><input type='text' name='address' value=".$row["address"]."> </td></tr>";
					echo "<tr><td>Gender: </td><td><input type='text' name='gender' value=".$row["gender"]."></td></tr>";
					echo "<tr><td>country: </td><td><input type='text' name='country' value=".$row["country"]."></td></tr>";
					echo "<tr><td>Hobby: </td><td><input type='text' name='hobbies' value=".$row["hobbies"]."></td></tr>";
		
					echo "</table>";
					echo "</form>";
				
					
					
						
						/*$update_Query=mysqli_query($conn,"update tbl_login set email=".$_POST["email"].", name=".$_POST["name"].",address=".$_POST["address"].",gender="			.$_POST["gender"].",country=".$_POST["country"].",hobbies=".$_POST["hobbies"]." where id=".$_GET["id"]."");
						
						*/
					
			}
		}

?>
</body>
</html>

<?php
	if(isset($_POST['Update']))
	{
		$update_Query=mysqli_query($conn,'update tbl_login set email="'.$_POST['email'].'", name="'.$_POST["name"].'" , address="'.$_POST["address"].'" , gender="'			.$_POST["gender"].'" , country="'.$_POST["country"].'" , hobbies="'.$_POST["hobbies"].'"  where id="'.$_GET['id'].'" ');
		
		echo "<script>
		window.opener.location.reload();
		parent.close();
		</script>";
	}
?>