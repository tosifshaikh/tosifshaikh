<?php
require_once('class.phpmailer.php');
require_once("includes/siteconfig.php");



function SendMail($fromemail,$fromname,$toemail,$toname,$subject,$body)
{

$Shost = $GLOBALS['SMTPHost'];
$SUserName = $GLOBALS['SMTPUserName'];
$SPassword = $GLOBALS['SMTPPassword'];
$SPort = $GLOBALS['SMTPPort'];
$SSSL = $GLOBALS['SMTPSSL'];
$SAuth = $GLOBALS['SMTPAUTH'];
	
  
  $host = $Shost;
  $username = $SUserName;
  $password = $SPassword;
  $smtpssl = $SSSL;
  $smtpport = $SPort;
  
  echo $host."<br/>";
  echo $username."<br/>";
  echo $password."<br/>";
  echo $smtpssl."<br/>";
  echo $smtpport."<br/>";
  echo $SAuth."<br/>";

  $mail = new PHPMailer();
  $mail->IsSMTP();
  if($smtpssl == 1)
  {
      $mail->SMTPSecure = 'ssl';
  }
  if($smtpport != "")
      $mail->Port = $smtpport;
  $mail->SMTPDebug = 1;
  $mail->SMTPAuth      = $SAuth;                 // enable SMTP authentication
  $mail->Host          = $Shost; // sets the SMTP server
  $mail->Username      = $username; // SMTP account username
  $mail->Password      = $password;        // SMTP account password
  $mail->port		= $smtpport;
  $mail->ssl			= $smtpssl;
  $mail->AddAddress($toemail,$toname);
  $mail->SetFrom($fromemail, $fromname);
  $mail->AddReplyTo($fromemail, $fromname);
      
  $mail->Subject       = $subject;
  $mail->MsgHTML($body);
  if(!$mail->Send())
  {
	exit(0);
  }
}

?>