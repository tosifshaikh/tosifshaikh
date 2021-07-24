<?php
require_once('connection.php');
if(isset($_GET['email']))
{
	$email=$_GET['email'];
}
$selquery="select * from tbl_login where email='".$_GET['email']."' ";
$result=mysqli_query($conn,$selquery);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<?php
while($row=mysqli_fetch_array($result))
{
?>
<table width="60%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="2" align="center"> <?php  echo "<font size='+2'>Welcome '".$email."'</font>"; ?></td>
  </tr>
  <tr>
    <td><?php if(empty($row[8])){echo "<img src='images/th.jpg' height='50' width='50' />"; } ?></td>
   
  </tr>
</table>
</body>
</html>
<?php
}
?>