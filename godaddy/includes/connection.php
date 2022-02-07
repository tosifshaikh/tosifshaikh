<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//ini_set("upload_max_filesize","10M");
//ini_set("post_max_size","10M");
//$con=mysqli_connect("<db location>","<db username>","<db Password>","<db name>");
$con=mysqli_connect("localhost","zipcrockusr","zip@420","zipcrock");

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


function GetIPAddress()
{    
    $ipaddress = "";
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    }   
	return $ipaddress;
}
function GetPageContent($pagename)
{
	$pagecontent = "";
	$con=mysqli_connect("localhost","idealinf_zipcrcU","zipcrcU123","idealinf_zipcroc");
	$content = mysqli_query($con2,"SELECT bodyconent FROM pagecontent where pagename='".mysqli_real_escape_string($con2,$pagename)."'");
	$rowpage = mysqli_fetch_array($content);
	if(sizeof($rowpage) > 0)
		$pagecontent = $rowpage["bodyconent"];
	mysqli_close($con2);
	return $pagecontent;
}
function GetPageTitle($pagename)
{
	$pagetitle = "";
  $con=mysqli_connect("localhost","zipcrocuser","Z!pCr0c","zipcroc");
	$content = mysqli_query($con2,"SELECT title FROM pagecontent where pagename='".mysqli_real_escape_string($con2,$pagename)."'");
	$rowpage = mysqli_fetch_array($content);
	if(sizeof($rowpage) > 0)
		$pagetitle = $rowpage["title"];
	mysqli_close($con2);
	return $pagetitle;
}
function GetPageMeta($pagename)
{
	$pagemeta = "";
  $con=mysqli_connect("localhost","idealinf_zipcrcU","zipcrcU123","idealinf_zipcroc");
	$content = mysqli_query($con2,"SELECT meta FROM pagecontent where pagename='".mysqli_real_escape_string($con2,$pagename)."'");
	$rowpage = mysqli_fetch_array($content);
	if(sizeof($rowpage) > 0)
		$pagemeta = $rowpage["meta"];
	mysqli_close($con2);
	return $pagemeta;
}

function CURDATE(){
  return date("Y/m/d");
}
function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$^';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
	
function simple_encrypt($data){
  return (base64_encode (convert_uuencode ($data)));
}

function simple_decrypt($data){
  return (convert_uudecode (base64_decode ($data)));
}

function simple_encrypt2($text)
{
	  $salt ='Z1pCr0C';
    return trim(base64_encode(mcrypt_encrypt(MCRYPT_SERPENT_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_SERPENT_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}

function simple_decrypt2($text)
{
	  $salt ='Z1pCr0C';
    return trim(mcrypt_decrypt(MCRYPT_SERPENT_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_SERPENT_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}
?>