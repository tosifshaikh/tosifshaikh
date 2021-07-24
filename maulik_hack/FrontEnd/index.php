<?php

if(!isset($_SESSION["userToken"]))
{
	include_once("view/loginView.php");
	exit();
}
?>