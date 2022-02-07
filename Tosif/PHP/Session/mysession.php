<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<form method="post">
<input type="submit" name="btn" value="btn"/>
</form>
</body>
</html>

<?php

	if(isset($_POST['btn']))
	{
		header("location:Count.php");	
	}
	
?>