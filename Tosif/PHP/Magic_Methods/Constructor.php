<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<?php if($message!="") { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>

<form name="form1" method="post" action="loginCheck.php">
	<table width="33%"  border="0" cellspacing="1" cellpadding="1" align="center">
  <tr>
    <td>UserName :</td>
    <td><input type="text" name="txtUser" id="txtUser" ></td>
  </tr>
  <tr>
    <td>Password :</td>
    <td>
      <label for="txtPass"></label>
      <input type="text" name="txtPass" id="txtPass"> 
        
       
         </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
      <input type="submit" name="btnLogin" id="btnLogin" value="Submit"> &nbsp;&nbsp;&nbsp;&nbsp; <input name="chCookie" type="checkbox" value="checkbox">Remember me?   
    </td>
  </tr>
  
<tr><td>New User? </td><td> <a href="signup_form.php">Sign Up</a> </td></tr>
<tr><td colspan="2" align="center"> <?php if(isset($_GET['msg'])){
	echo "<font size='+2' color='#FF0000' >'".$_GET['msg']."'</font>";
	} ?></td></tr>
</table>
</form>
<?php

?>
</body>
</html>