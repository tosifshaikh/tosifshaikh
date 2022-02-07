<?php ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(!isset($_SESSION)) 
{ 
	session_start(); 
} 
require_once("includes/siteconfig.php");

require_once("includes/connection.php");

$cust_id = (isset($_SESSION["custid"]) && $_SESSION["custid"] != "") ? $_SESSION["custid"] : '';

if($cust_id == '')
	header('location:sign-in.php');

require_once("includes/connection.php");

//Store SAddress GMaplink
//FName ContactNo CAddress CGMaplink
//deliverySlots
//Comments
	$FullName = "";
	$Contact="";
	$Alternate="";
	$Email="";
	$Zip = "";
	$strUserCheck = "SELECT * FROM `customer` WHERE id = '". mysqli_real_escape_string($con,$cust_id) ."' "; 
	$dbRes = mysqli_query($con,$strUserCheck);
	$tarray = mysqli_fetch_array($dbRes);
	if(!empty($tarray))
	{
		$FullName = $tarray["FullName"];
		$Contact = $tarray["phone"];
		$Alternate = $tarray["alternatecontact"];
		$Email = $tarray["email"];
		$Zip = $tarray["zipcode"];
	}

$storename=""; 
$storeadd=""; 
$storegmap=""; 
$toname=""; 
$tophone=""; 
$toadd=""; 
$togmap=""; 
$deliveryslots=""; 
$note=""; 
$ipadd=""; 
if (!empty($_POST))
{
	$storename= (isset($_POST["Store"]) && $_POST["Store"] != "") ? $_POST["Store"] : '';
	$storeadd= (isset($_POST["SAddress"]) && $_POST["SAddress"] != "") ? $_POST["SAddress"] : '';
	$storegmap= (isset($_POST["GMaplink"]) && $_POST["GMaplink"] != "") ? $_POST["GMaplink"] : '';
	$toname= $FullName;
	$tophone= $Contact;
	$toadd= (isset($_POST["CAddress"]) && $_POST["CAddress"] != "") ? $_POST["CAddress"] : '';
	$togmap= (isset($_POST["CGMaplink"]) && $_POST["CGMaplink"] != "") ? $_POST["CGMaplink"] : '';
	$deliveryslots= (isset($_POST["deliverySlots"]) && $_POST["deliverySlots"] != "") ? $_POST["deliverySlots"] : '';
	$note= (isset($_POST["Comments"]) && $_POST["Comments"] != "") ? $_POST["Comments"] : '';
	$ipadd= GetIPAddress();

	
	$strAddIns = "INSERT INTO `customerordermaster` (`cust_id`, `storename`, `storeadd`, `storegmap`, `toname`,`tophone`, `toadd`,`togmap`, `deliveryslots`, `note`, `ipadd`) VALUES(". $cust_id .",'". mysqli_real_escape_string($con,$storename) ."','". mysqli_real_escape_string($con,$storeadd) ."','". mysqli_real_escape_string($con,$storegmap) ."','". mysqli_real_escape_string($con,$toname) ."','". mysqli_real_escape_string($con,$tophone) ."','". mysqli_real_escape_string($con,$toadd) ."','". mysqli_real_escape_string($con,$togmap) ."','". mysqli_real_escape_string($con,$deliveryslots) ."','". mysqli_real_escape_string($con,$note) ."','". mysqli_real_escape_string($con,$ipadd) ."')";
	mysqli_query($con,$strAddIns);
	$_SESSION["order_id"] = "";
	$order_id = "0";
	
	$strUserCheck = "SELECT id FROM `customerordermaster` WHERE cust_id = '". mysqli_real_escape_string($con,$cust_id) ."' order by id desc limit 1"; 
	$ordercode="";	
	$custemail="";
	$dbRes1 = mysqli_query($con,$strUserCheck);
	$tarray1 = mysqli_fetch_array($dbRes1);
	if(!empty($tarray1))
	{
		$order_id = $tarray1["id"]; 
		
		$strCustSql = "SELECT email FROM `customer` WHERE id = '". mysqli_real_escape_string($con,$cust_id) ."'"; 
		
		$dbRes2 = mysqli_query($con,$strCustSql);
		$tarray2 = mysqli_fetch_array($dbRes2);		
		$custemail = $tarray2["email"]; 

		$ordercode=date("y").sprintf("%03d", $cust_id).date("m").sprintf("%03d", $order_id).date("d");//ddCUSTIDmmORDERIDyy
		$strAddIns = "update `customerordermaster` set OrderCode='". $ordercode ."' where id=". $order_id ."";
		
		mysqli_query($con,$strAddIns);
		$_SESSION["order_id"] = $order_id; 
		
		require_once("sendmail.php");

		$body = "<table cellspacing='0' cellpadding='0' width='100%'>";
		$body .= "<tr><td align='center'><img src='".$WebLocation."images/zipcroc.png' style='border:0px;'/></td></tr>";
		$body .= "<tr><td align='center'><strong>".$SiteName." Order Confirmation Mail</strong></td></tr>";
		$body .= "<tr><td><hr/></td></tr>";
		$body .= "<tr><td style='height:30px;'>Dear Admin,</td></tr>";
		$body .= "<tr><td style='height:30px;'>An order has been placed by ". $toname ."</td></tr>";
		$body .= "<tr><td style='height:30px;'>The order details are as below:</td></tr>";
		$body .= "<tr><td style='height:30px;'><strong>Order Code:</strong>". $ordercode ."</td></tr>";
		$body .= "<tr><td style='height:30px;'><strong>Store:</strong>".$storename."<br/><strong>Store Address:</strong>".$storeadd."<br/><a href='". $storegmap ."'>Store Location</a><br/></td></tr>";
		$body .= "<tr><td style='height:30px;'><hr/></td></tr>";
		$body .= "<tr><td style='height:30px;'><strong>Customer Name:</strong>".$toname."<br/><strong>Customer Contact:</strong>".$tophone."<br/><strong>Delivery Address:</strong>".$toadd."<br/><a href='". $togmap ."'>Delivery Location</a><br/></td></tr>";
		$body .= "<tr><td style='height:30px;'><strong>Delivery Slot Time:</strong>". $deliveryslots ."</td></tr>";
		$body .= "<tr><td style='height:30px;'><strong>Comments:</strong>". $note ."</td></tr>";
		$body .= "<tr><td style='height:30px;'>This request was made from IP address :<strong>". $ipadd ."</strong></td></tr>";
		$body .= "<tr><td><br/><br/>Regards,</td></tr>";
		$body .= "<tr><td>".$SiteName." Team</td></tr>";
		$body .= "</table>";
		$subject = $SiteName." Order Confirmation Mail Order# : ".$ordercode;
		SendMail($custemail,$toname, $AdminMailAddress,$SiteName,$subject,$body);


		$body = "<table cellspacing='0' cellpadding='0' width='100%'>";
		$body .= "<tr><td align='center'><img src='".$WebLocation."images/zipcroc.png' style='border:0px;'/></td></tr>";
		$body .= "<tr><td align='center'><strong>".$SiteName." Order Confirmation Mail</strong></td></tr>";
		$body .= "<tr><td><hr/></td></tr>";
		$body .= "<tr><td style='height:30px;'>Dear ". $toname .",</td></tr>";
		$body .= "<tr><td style='height:30px;'>Thank you for placing order with us.</td></tr>";
		$body .= "<tr><td style='height:30px;'>The order details are as below:</td></tr>";
		$body .= "<tr><td style='height:30px;'><strong>Order Code:</strong>". $ordercode ."</td></tr>";
		$body .= "<tr><td style='height:30px;'><strong>Store:</strong>".$storename."<br/><strong>Store Address:</strong>".$storeadd."<br/><a href='". $storegmap ."'>Store Location</a><br/></td></tr>";
		$body .= "<tr><td style='height:30px;'><hr/></td></tr>";
		$body .= "<tr><td style='height:30px;'><strong>Customer Name:</strong>".$toname."<br/><strong>Customer Contact:</strong>".$tophone."<br/><strong>Delivery Address:</strong>".$toadd."<br/><a href='". $togmap ."'>Delivery Location</a><br/></td></tr>";
		$body .= "<tr><td style='height:30px;'><strong>Delivery Slot Time:</strong>". $deliveryslots ."</td></tr>";
		$body .= "<tr><td style='height:30px;'><strong>Comments:</strong>". $note ."</td></tr>";
		$body .= "<tr><td style='height:30px;'>This request was made from IP address :<strong>". $ipadd ."</strong></td></tr>";
		$body .= "<tr><td><br/><br/>Regards,</td></tr>";
		$body .= "<tr><td>".$SiteName." Team</td></tr>";
		$body .= "</table>";
		$subject = $SiteName."Order Confirmation Mail Order# : ".$ordercode;

		SendMail($AdminMailAddress,$SiteName,$custemail,$toname,$subject,$body);
	}
	
	

	header('location:my-account.php?msg=An Order has been Placed successfully.&typeid=2');
}
echo "Test";
?>