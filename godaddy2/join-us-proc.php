<?php ob_start();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$Name= "";
$Email= "";
$Contact= "";

require_once("includes/siteconfig.php");
if (!empty($_POST))
{
	$Name = (isset($_POST["Name"]) && $_POST["Name"] != "") ? $_POST["Name"] : '';
	$Email = (isset($_POST["Email"]) && $_POST["Email"] != "") ? $_POST["Email"] : '';
	$Contact = (isset($_POST["Contact"]) && $_POST["Contact"] != "") ? $_POST["Contact"] : '';

	$body = "<table cellspacing='0' cellpadding='0' width='100%'>";
	$body .= "<tr><td align='center'><img src='".$WebLocation."images/zipcroc.png' style='border:0px;'/></td></tr>";
	$body .= "<tr><td align='center'><strong>".$SiteName." Resume Mail</strong></td></tr>";
	$body .= "<tr><td><hr/></td></tr>";

	$body .= "<tr><td style='height:30px;'>Dear <strong>Admininstrator</strong>,</td></tr>";
	$body .= "<tr><td style='height:30px;'>A resume has been uploaded by a candidate. Below are candidate details.</td></tr>";
	$body .= "<tr><td>Name:".$Name."</td></tr>";
	$body .= "<tr><td>Email:".$Email."</td></tr>";
	$body .= "<tr><td>Contact Number:".$Contact."</td></tr>";

	$body .= "</table>";


	$toemail = $AdminMailAddress;
	$toname = $SiteName;

	  $subject = $SiteName." Resume Mail";
	  $host = $SMTPHost;
	  $username = $SMTPUserName;
	  $password = $SMTPPassword;
	  $smtpssl = $SMTPSSL;
	  $smtpport = $SMTPPort;
	  $smtpauth=$SMTPAUTH;
	  require_once('class.phpmailer.php');

	  $mail = new PHPMailer();
	  $mail->IsSMTP();
	  if($smtpssl == 1)
	  {
		  $mail->SMTPSecure = 'ssl';
	  }
	  if($smtpport != "")
		  $mail->Port = $smtpport;
	  $mail->SMTPDebug = 1;
	  $mail->SMTPAuth      = $smtpauth;                 // enable SMTP authentication
	  $mail->Host          = $host; // sets the SMTP server
	  $mail->Username      = $username; // SMTP account username
	  $mail->Password      = $password;        // SMTP account password
	  $mail->port		= $smtpport;
	  $mail->ssl			= $smtpssl;

	  $mail->AddAddress($toemail,$toname);
	  $mail->SetFrom($Email, $Name);
	  $mail->AddReplyTo($Email, $Name);
	  
	  if (isset($_FILES['CV']) && $_FILES['CV']['error'] == UPLOAD_ERR_OK) 
	  {
			//echo 'Tmp:'.$_FILES['CV']['tmp_name'].'<br/>Name'.$_FILES['CV']['name'];
	      $mail->AddAttachment($_FILES['CV']['tmp_name'],$_FILES['CV']['name']);
      }
	  $mail->Subject       = $subject;
	  $mail->MsgHTML($body);
	  if($mail->Send())
	  	echo "<script>window.location.href='thankyou-job-post.php'</script>";
	//  header('location:thankyou-job-post.php');
}

?>