<?php 
ob_flush();error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
if(!isset($_SESSION)) 
{ 
	session_start(); 
} 
require("../includes/connection.php") 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ZipCroc - Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="en-us" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="css/base.css"></link>
        
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery.validate.js"></script>
<script type="text/javascript">
$(document).ready(function () {
	$("#frmlogin").validate({
		rules: {
			txtusername: "required",
			txtpassword: "required"
		},
		messages: {
			txtusername: "*",
			txtpassword: "*"
		}
	});
});
</script>
<body>
	<table border="0" cellspacing="0" cellpadding="0" width="1000" summary="Administrative Area" align="center">
			<tr>
				<td>
<div style="padding-top: 100px;">
        <center>
		
		
            <table border="0" cellspacing="0" cellpadding="0" width="388" summary="Login Area"
                align="center">
                <tbody>
                    <tr>
                        <td align="left">
						<?php
							if (!empty($_POST) && isset($_POST['btnLogin']))
							{
								$txtusername = (isset($_POST["txtusername"]) && $_POST["txtusername"] != "") ? $_POST["txtusername"] : '';
								$txtpassword = (isset($_POST["txtpassword"]) && $_POST["txtpassword"] != "") ? $_POST["txtpassword"] : '';
							
								if(trim($txtusername)== "" || trim($txtpassword)== "")
								{
						?>
								<div id="div_Error" class="PanError">Please Enter Username and Password.</div>
						<?php
								}
								else
								{
									$strUserCheck = "SELECT usrnm FROM tbladmin WHERE usrnm = '". mysqli_real_escape_string($con,$txtusername) ."' and passfield = '". mysqli_real_escape_string($con,$txtpassword) ."' "; 
									//echo  $strUserCheck;
									$tot = mysqli_fetch_array(mysqli_query($con,$strUserCheck));
									if(!empty($tot))
									{
										$_SESSION["validadmin"]=1;
										header( 'Location:Orders-listing.php?otype=0') ;
									}
									else
									{
						?>
										<div id="div_Error" class="PanError">Invalid Login Credentials.</div>
						<?php
									}
								}
							}	
						?>
                            
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <img src="images/head_login.jpg" />
                        </td>
                    </tr>
                    <tr>
                        <td id="loginarea" align="center">
                            <!-- START: Login Content Area -->
                            <table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse">
                                <tr>
                                    <td><form method="post" id="frmlogin">
                                        <table border="0" cellpadding="5" cellspacing="0">
                                            
                                            <tr>
                                                <td valign="top">
                                                    Username *:
                                                </td>
                                                <td align="left" width="175">
                                                    <input name="txtusername" type="text" id="txtusername" style="width:150px;" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">
                                                    Password *:
                                                </td>
                                                <td align="left">
                                                    <input name="txtpassword" type="password" id="txtpassword" style="width:150px;" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;
                                                    
                                                </td>
                                                <td align="left">
                                                    <input type="submit" name="btnLogin" value="Submit" class="buttons" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                </td>
                                                <td align="left">
                                                    <a href="forgotten-password.php">Forgot Password?</a>
                                                </td>
                                            </tr>
                                        </table></form>
                                    </td>
                                </tr>
                            </table>
                            <!-- END: Login Content Area -->
                        </td>
                    </tr>
                </tbody>
            </table>
        </center>
    </div>
    
					</td>
				</tr>
		</table>
	</body>
</html>    			