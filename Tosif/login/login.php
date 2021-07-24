<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<form action="index.php" method="post">
<table border="1" align="center">
<tr><td>

<H3>LOGIN ::</H3>
</td></tr>
<tr><td>
Username ::
<input type="text" id="untxt" name="untxt"/></td></tr>
</br>
</br>
<tr><td>
Password ::
<input type="text" id="pstxt" name="pstxt"/></td></tr>
</br>
<tr><td><input type="submit" id="button" value="submit" />	
<a href="index.html" style="text-decoration:none"><input type="button" id="signUp" value="signUp" /></a></td></tr>
</table>
</form>
</body>
</html>

<?php
session_unset();
?>

