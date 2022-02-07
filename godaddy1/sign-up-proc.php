<?php ob_start();
////error_reporting(E_ALL);
////ini_set('display_errors', 1);

if(!isset($_SESSION)) 
{ 
	session_start(); 
} 
require_once("includes/siteconfig.php");

if(!empty($_SESSION["custvalid"]) && $_SESSION["custvalid"]==1)
	header('location:my-account.php');

require_once("includes/connection.php");
$_SESSION["Error"] ="";
$Name= "";
$Email= "";
$Zipcode= "";
$errmsg = "";
$CustPass = randomPassword();
if (!empty($_POST))
{
	$Name= (isset($_POST["Name"]) && $_POST["Name"] != "") ? $_POST["Name"] : '';
	$Email= (isset($_POST["Email"]) && $_POST["Email"] != "") ? $_POST["Email"] : '';
	$Zipcode= (isset($_POST["Zipcode"]) && $_POST["Zipcode"] != "") ? $_POST["Zipcode"] : '';
}
	$_SESSION["Name"] = trim($Name);
	$_SESSION["Email"] = trim($Email);
	$_SESSION["Zipcode"] = trim($Zipcode);

if(trim($Name) == "" || trim($Email) == "" || trim($Zipcode) == ""){
	$errmsg = "All fields are required";
	$_SESSION["Error"] = $errmsg;
	header('location:sign-up.php');
}
else{
		$strUserCheck = "SELECT count(*) as tot FROM `customer` WHERE email = '". mysqli_real_escape_string($con,$Email) ."' "; 
		$tarray = mysqli_query($con,$strUserCheck);
		$tot = mysqli_fetch_array($tarray);
		if($tot["tot"] != "0")
		{
			$errmsg = "The email you have entered is already exists. Please enter another email.";
			$_SESSION["Name"] = trim($Name);
			$_SESSION["Email"] = trim($Email);
			$_SESSION["Zipcode"] = trim($Zipcode);
			$_SESSION["Error"] = $errmsg;
			header('location:sign-up.php');    
		}
		else
		{
			
		 	$strins = "INSERT INTO `customer`(`FullName`, `email`, `phone`, `alternatecontact`, `zipcode`, `custpass`, `regdate`, `ipaddress`, `authenticated`) ";
            $strins .= "VALUES ('".mysqli_real_escape_string($con,$Name)."','".mysqli_real_escape_string($con,$Email)."','','','".mysqli_real_escape_string($con,$Zipcode)."','".mysqli_real_escape_string($con,$CustPass)."',CURDATE(),'".GetIPAddress()."',0)";
            mysqli_query($con,$strins);
			
			$cust_id="";
			$strUserCheck = "SELECT * FROM `customer` WHERE email = '". mysqli_real_escape_string($con,$Email) ."' "; 
			$dbRes = mysqli_query($con,$strUserCheck);
			$tarray = mysqli_fetch_array($dbRes);
			if(!empty($tarray))
			{
				$cust_id = $tarray["id"];
			}

		//	$stringToEncrypt = simple_encrypt("CustID=".$cust_id."&Pass=".$CustPass);

			$link= "http://upliftcreditrepair.com/zipcrock/sign-in.php";

			$body = "<table cellspacing='0' cellpadding='0' width='100%'>";
			$body .= "<tr><td align='center'><img src='".$WebLocation."images/zipcroc.png' style='border:0px;'/></td></tr>";
			$body .= "<tr><td align='center'><strong>".$SiteName." Registration Activation Mail</strong></td></tr>";
			$body .= "<tr><td><hr/></td></tr>";

			$body .= "<tr><td style='height:30px;'>Dear <strong>". $Name ."</strong>,</td></tr>";
			$body .= "<tr><td style='height:30px;'>Thank you for your registration with us.</td></tr>";
			$body .= "<tr><td style='height:30px;'>Copy and Paste below link in your browser addressbar</td></tr>";
			$body .= "<tr><td style='height:30px;'>".$link."</td></tr>";
			$body .="<tr><td style='height:30px;'><strong>Your Login Details</strong></td></tr>";
			$body .="<tr><td style='height:30px;'>Email:".$Email."</td></tr>";
			$body .="<tr><td style='height:30px;'>Password:".$CustPass."</td></tr>";
			$body .= "<tr><td><br/>Regards,</td></tr>";
			$body .= "<tr><td>".$SiteName." Team</td></tr>";
			$body .= "</table>";
			$subject = $SiteName." Registration Activation Mail";

			require_once("sendmail.php");
			SendMail($AdminMailAddress,$SiteName, $Email,$Name,$subject,$body);

			//$strAddIns = "INSERT INTO `customeraddress` (`cust_id`, `address1`, `addLink1`, `address2`, `addLink2`,`address3`, `addLink3`,`address4`, `addLink4`) ";
			//$strAddIns .= "VALUES(". $cust_id .",'','','','','','','','')";
			//mysqli_query($con,$strAddIns);
			//$_SESSION["custvalid"] = 1;
			//$_SESSION["custid"]  = $cust_id;
			
			$_SESSION["Name"] = "";
			$_SESSION["Email"] = "";
			$_SESSION["Zipcode"] = "";

			header('location:thankyou.php');//header('location:my-account.php');

		}
	}
?>