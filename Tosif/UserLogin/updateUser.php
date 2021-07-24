<?php
session_start();
if(!isset($_SESSION['uname']))
{
	header("location:form.php");
}
include('conn.php');

//$uname=$_SESSION['uname'];
?>

<?php  
if(isset($_GET['id']))
{
	$id=$_GET['id'];
	$sql='select * from member WHERE mem_id='.$_GET['id'].'';
	$res=mysqli_query($conn,$sql);
	
}?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update User Details</title>
<link rel="stylesheet" type="text/css" href="main.css">
<script>
//function winClose()
//{window.close();}
</script>

</head>

<body background="Upload/nature.jpg">

<form method="post" >
<?php 
while($row=mysqli_fetch_array($res))
{
?>
<table align="center" style="margin-top:100px">

<tr><td colspan="2" align="center"><img src="<?php echo $row[7]; ?>" height="150" width="150"></td></tr>
<tr>
<td>First Name :</td><td><input type="text" name="txtfnam" value="<?php echo $row[3]; ?>"></td></tr>
<tr><td>Last Name :</td><td><input type="text" name="txtlnam" value="<?php echo $row[4]; ?>"></td></tr>
<tr><td>Address :</td><td><input type="text" name="txtadd" value="<?php echo $row[5]; ?>"></td></tr>
<tr><td>Contact Number :</td><td><input type="text" name="txtct" value="<?php echo $row[6]; ?>"></td></tr>
<tr><td>Gender :</td>

<td><input type="radio" name='gender' value="M" <?php if($row[8]=="M"){?>checked="checked"<?php } ?>>Male
	<input type="radio" name='gender' value="F" <?php if($row[8]=="F"){?>checked="checked"<?php } ?>>Female
</td></tr>


<tr><td colspan="2" align="center"><input type="submit" class="myButton" value="Save" name='submit' />
<input type="button" value="Cancel" class="myButton" onclick="window.close();"/></td></tr>
</table>
<?php } ?>
</form>

</body>
</html>
<?php 
if(isset($_POST['submit']))
{	
	$fnm=$_POST['txtfnam'];
	$lnm=$_POST['txtlnam'];
	$add=$_POST['txtadd'];
	$contact=$_POST['txtct'];
	$gen=$_POST['gender'];
	$sql2="update member SET fname='$fnm',lname='$lnm',address='$add',contact='$contact',gender='$gen' where mem_id='$id'";
	$res2=mysqli_query($conn,$sql2);
	echo '<script> window.opener.location.reload();parent.close()</script>';
} ?>
