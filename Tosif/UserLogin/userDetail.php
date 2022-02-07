<?php
session_start();
if(!isset($_SESSION['uname']))
{
	header("location:form.php");
}
include('conn.php');


if(isset($_GET['uname']))
{
	$unm=$_GET['uname'];
	$sql="select * from member WHERE username='$unm'";
	$res=mysqli_query($conn,$sql);

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="main.css">
<script >
function updateWindow(id)
{
		window.open('updateUser.php?id='+id,'Update Details','height=500,width=500,resizable=no');
}

</script>
</head>
<body background="Upload/nature.jpg">

<?php	while($row=mysqli_fetch_array($res)){ ?>
<table align="center" style="margin-top:100px;font-size:24px" >
<tr><td colspan="2" align="center" style="font-size:50px;color:#9CC"> Welcome  <?php echo $row[3]; ?></td></tr>	
<tr><td colspan="2" align="center">
<?php if(empty($row[7])) 
{
	echo "<img src='D:/th.jpeg' height='150' width='150' />";
}
else
{
	echo "<img src=D:/'".$row[7]."' height='150' width='150' />";
}



 ?></td></tr>
<tr><td>First Name :</td><td><?php echo $row[3]; ?></td></tr>
<tr><td>Last Name :</td><td><?php echo $row[4]; ?></td></tr>
<tr><td>Address :</td><td><?php echo $row[5]; ?></td></tr>
<tr><td>Contact Number :</td><td><?php echo $row[6]; ?></td></tr>
<tr><td>Gender :</td>

<td><input type="radio"  name='gender' value="M" <?php if($row[8]=="M"){?>checked="checked"<?php } ?>>Male
	<input type="radio" name='gender' value="F" <?php if($row[8]=="F"){?>checked="checked"<?php } ?>>Female
</td></tr>

<tr><td colspan="2">
<input type="button" class="myButton" value="Update" onclick="updateWindow(<?php echo $row[0] ?>)"/>
<a class="myButton" href="logout.php" >Log Out</a>
</td></tr>

</table>
<?php } ?>

</body>
</html>
