<?php
session_start();
if(!isset($_SESSION['admin']))
{
header("location:form.php");
}
include('conn.php');

	$sql="select * from member WHERE flg=0";
	$res=mysqli_query($conn,$sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script>
var obj;
var url;
function Oncall(flg)
{
	
	var mytd=document.getElementById('mytd');
	var txtSearch=document.getElementById('txtSearch');
	if(flg==1)
	{
      var url="request.php";	
 	 
	}
	if(flg==2)
	{
	  var url="reject.php";	
	}
	if(flg==3)
	{
		var url="searchData.php?wrd="+txtSearch.value;
	}
	
	//alert(flg)
	//if(flg==1)
//	{
//		var url="searchData.php?wrd="+txtSearch.value;
//	}
//	else if(flg==2)
//	{
//	  var url='request.php';	
//	}
//	else if(flg==3)
//	{
//	  var url='reject.php';	
//	}
	
	if(window.XMLHttpRequest)
	{
		obj=new XMLHttpRequest();
	}
	obj.onreadystatechange=function()
	{
		if(obj.readyState==4 && obj.status==200)
		{
			mytd.innerHTML=obj.responseText;
		}
	}
	obj.open("POST",url,true);
	obj.send();
}
</script>
<link rel="stylesheet" type="text/css" href="main.css">
<style>
a
{
	text-decoration:none;
	cursor:pointer;
}
</style>

</head>
<body background="Upload/nature.jpg" onload="
<?php if(isset($_SESSION['flag']))	{?>Oncall(<?php echo $_SESSION['flag'] ?>)<?php }?>"><?php    /*?> <?php if(isset($_GET['flag']))	{?>Oncall(<?php echo $_GET['flag'] ?>)<?php }?>"><?php */?>

<!--<form method="post">-->
<table width="40%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr><td colspan="5" align="center" style="font-size:40px;color:#9CC"> Welcome To Admin Panel</td></tr>
  <tr>
    <td colspan="5" align="left">
    <?php include('adminPanel.php');?>

</td>
  </tr>
  
  <tr><td colspan="3">
  <table border="1" width="100%" id="mytd">
  </table>

 </td></tr>
   
</table>
<!--</form>-->

</body>
</html>
