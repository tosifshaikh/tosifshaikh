<?php ob_start();
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	} 

	$printstr = "";
	$mobstr="";
	if(!empty($_SESSION["custvalid"]) && $_SESSION["custvalid"]==1)
	{
		$printstr = "<li class='active dropdown'><a href='my-account.php'>Welcome ". $_SESSION["custname"] .",</a></li>";
		$printstr .= "<li class='dropdown'><a href='sign-out-proc.php' style='padding-right:0px;'>Sign Out</a></li>";

		
		$mobstr = "<li class='ma5-li-1'><a href='sign-up.php'>SIGN UP</a></li>";
	}
	else{
		$printstr = "";//"<li class='dropdown'> <a href='my-account.php'>GUEST</a></li>";
		$printstr .= "<li class='dropdown'> <a href='sign-in.php'>SIGN IN</a></li>";
		$printstr .= "<li class='dropdown'><a href='sign-up.php' style='padding-right:5px;'>SIGN UP</a></li>";

		$mobstr = "";//"<li class='ma5-li-1'><a href='my-account.php'>GUEST</a></li>";          
        $mobstr .= "<li class='ma5-li-2'><a href='sign-in.php'>SIGN IN</a></li>";
        $mobstr .= "<li class='ma5-li-3'><a href='sign-up.php'>SIGN UP</a></li>";
	}

	$retval = "{\"web\":\"".$printstr."\",\"mob\":\"".$mobstr."\"}";

	echo $retval;
?>