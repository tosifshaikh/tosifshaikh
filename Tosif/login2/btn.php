<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>

<!--<script>
document.getElementById("bt1").submit();
document.getElementById("bt2").submit();
</script>-->
</head>

<body>
<form method="post" action="loginCheck.php">
<input type="submit" name="bt1" value="bt1"/>
</form>
<form method="post" >
<input type="submit" name="bt2" value="bt2"/>
</form>
</body>
</html>

<?php
	
	if(isset($_POST['bt2']))
	{
		
		
		header("location:index.php");
		
	}
?>