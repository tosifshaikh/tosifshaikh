<?php
session_start();
if(isset($_SESSION['uname']))
{
unset($_SESSION['uname']);
session_destroy();
header("location:form.php");
}
else if(isset($_SESSION['admin']))
{
unset($_SESSION['admin']);
session_destroy();
header("location:form.php");
}
?>