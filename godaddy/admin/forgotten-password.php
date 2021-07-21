<?php ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$txtmail = "";
$errmsg = "";
$sucmsg = "";
require_once("../includes/siteconfig.php");
if(!empty($_POST))
{
	$txtmail = isset($_POST["txtEmail"]) ? $_POST["txtEmail"] : "";
	if($txtmail != "")
	{
		require("../includes/connection.php");
		$strUserCheck = "Select usrnm, passfield from tbladmin where aemail = '". mysqli_real_escape_string($con,$txtmail) ."' "; 
		$tarray = mysqli_query($con,$strUserCheck);
		$num_rows = mysqli_num_rows($tarray);
		if($num_rows > 0)
		{
			$passrec = mysqli_fetch_array($tarray);

			$custpass = $passrec["passfield"];
			$name = $passrec["usrnm"];

			$ipaddress = GetIPAddress();

			$body = "<table cellspacing='0' cellpadding='0' width='100%'>";
			$body .= "<tr><td align='center'><img src='".$WebLocation."images/zipcroc.png' style='border:0px;'/></td></tr>";
			$body .= "<tr><td align='center'><strong>".$SiteName." Password Recovery Mail</strong></td></tr>";
			$body .= "<tr><td><hr/></td></tr>";

			$body .= "<tr><td style='height:30px;'>Dear Admin,</td></tr>";
			$body .= "<tr><td style='height:30px;'>This email was sent by ZipCroc in response to your request to recover your password.</td></tr>";
			$body .= "<tr><td style='height:30px;'>Your Account details are as below:</td></tr>";
			$body .= "<tr><td style='height:30px;'>Username :<strong>". $name ."</strong></td></tr>";
			$body .= "<tr><td style='height:30px;'>Password :<strong>". $custpass ."</strong></td></tr>";
			$body .= "<tr><td style='height:30px;'>This request was made from IP address :<strong>". $ipaddress ."</strong></td></tr>";
			$body .= "<tr><td><br/><br/>Regards,</td></tr>";
			$body .= "<tr><td>".$SiteName." Team</td></tr>";
			$body .= "</table>";
			$subject = $SiteName."  Password Recovery Mail";
			
			require_once("../sendmail.php");
			SendMail($AdminMailAddress,$SiteName ,$AdminMailAddress,$SiteName ,$subject,$body);
			$sucmsg = "Your password sent to your email address.";
			$errmsg = "";
			session_unset ( void );
			header('location:index.php?msg='.$sucmsg);
		}
		else
			$errmsg="Invalid Email Address. Please enter registered email.";
	}
	else
		$errmsg="Invalid Email Address. Please enter registered email.";
}	
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
			txtEmail: "required"
		},
		messages: {
			txtEmail: "*"
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
					<?php if($errmsg != ""){ ?>
                    <tr>
                        <td align="left">
								<div id="div_Error" class="PanError"><?php echo $errmsg; ?></div>
                            
                        </td>
                    </tr>
					<?php } ?>
					<?php if($sucmsg != ""){ ?>
                    <tr>
                        <td align="left">
								<div id="div_Success" class="PanSuccess"><?php echo $sucmsg; ?></div>
                            
                        </td>
                    </tr>
					<?php } ?>
                    <tr>
                        <td>
                            <img src="images/head_forgotpass.jpg" />
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
                                                    Email :
                                                </td>
                                                <td align="left" width="175">
                                                    <input name="txtEmail" type="text" id="txtEmail" style="width:250px;" value="<?php echo $txtmail; ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;
                                                    
                                                </td>
                                                <td align="left">
                                                    <input type="submit" name="btnLogin" value="Submit" class="buttons" />
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