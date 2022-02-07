<?php
session_start();
//if(!isset($_SESSION['uname']))
//{
//	header("location:form.php");
//}
include('conn.php');


//if(isset($_GET['uname']))
//{
	//$unm=$_GET['uname'];
	$sql="select * from member WHERE flg=1";
	$res=mysqli_query($conn,$sql);

//}

?>

<form method="post">
<!--<table width="40%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr><td colspan="5" align="center" style="font-size:40px;color:#9CC"> Welcome To Admin Panel</td></tr>
  <tr>
    <td colspan="5" align="left">
    <?php //include('adminPanel.php');?>
</td>
  </tr>-->
<tr><th align="center">Id</th><th>UserName</th><th>Fname</th><th colspan="2">Action</th></tr>
<?php
while($row=mysqli_fetch_array($res))
{ ?>
<tr align="center"><td ><?php echo $row[0] ?></td><td><?php echo $row[1] ?></td><td ><?php echo $row[3] ?></td><td colspan="2" align="center"><a href='reject.php?reject_id=<?php echo $row[0] ?>' class="myButton2">Reject</a>

</td></tr>
<?php } ?>
<!--</table>-->
</form>

<?php
if(isset($_GET['reject_id']))
{
	$reject_id=$_GET['reject_id'];
	$approveQuery="update member set flg=0 where mem_id='$reject_id'";
	$result=mysqli_query($conn,$approveQuery);
	if($result){
		$_SESSION['flag']=2;
	header('location:Search.php');
}
}

?>