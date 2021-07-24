<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>

<?php
session_start(); 
//$_SESSION['views'] = 1; // store session data
//echo "Pageviews = ". $_SESSION['views']; //retrieve data

if(isset($_SESSION['views']))
{
	$_SESSION['views']=$_SESSION['views']+1;
}
else
{
	$_SESSION['views']=1;	
}

echo "Number of times views::".$_SESSION['views'];
?>