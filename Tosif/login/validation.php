<!DOCTYPE HTML> 
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
 <?php

	$un=$ps=$rps="";
	$myun = $myps = $myrps = "";
	
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		if(empty($_POST['uname']))
		{
			$un='not valid un...';
		}
		else
		{
			$myun=$_POST['uname'];	
		}
		
		if(empty($_POST['txtpass']))
		{
			$ps='not valid txtpass...';
		}
		else
		{
			$myps=$_POST['txtpass'];	
		}
		
		if(empty($_POST['txtpass']))
		{
			$rps='not valid txtpass222...';
		}
		else
		{
			$myrps=$_POST['txtpass'];	
		}
	}
	
	
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
<table width="60%" border="1" cellspacing="1" cellpadding="1" align="center">
  <tr>
    <td colspan="5"><div align="center">Sign up Form</div></td>
  </tr>
  <tr>
    <td colspan="5"><div align="left">Login Detail's</div></td>
  </tr>
  <tr>
    <td width="15%"><div align="right">User Name</div></td>
    <td colspan="2">
      <label for="txtemail"></label>
      <input type="text" name="uname" id="uname">
      <span class="error">* <?php echo $un;?></span>
    </td>
    <td>&nbsp;</td>
    <td width="23%">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Password</div></td>
    <td colspan="2"><input type="text" name="txtpass" id="txtpass"><span class="error">* <?php echo $ps;?></span></td>
     
    <td align="right">Retype Password</td>
    <td><input type="text" name="txtpass2" id="txtpass2"> <span class="error">* <?php echo $rps;?></span></td>
    
  </tr>
  <tr><td colspan="3">Profile Picture <input type="file" name="profile" /><img height="10"> 
  </td>
  </tr>
  <tr>
    <td colspan="2"><input type="submit" name="submit" value="SignUp" id="btnlg" >
    <input type="submit" name="submit" value="Cancel" id="btncancel" ></td>
  </tr>

  
</table>
</form>
<?php
	echo "<h2>Your Input:</h2>";
	echo $myun;
	echo "<br>";
	echo $myps;
	echo "<br>";
	echo $myrps;
?>
</body>
</html>