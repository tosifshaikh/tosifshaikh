<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script>
function download()
{
	var form=document.getElementById('frm');
	form.action="index.php?act=ZIP";
	form.submit();
}
</script>
</head>

<body>
<form id="frm" method="post" name="frm">
<textarea id="txtArea" name="txtArea"></textarea>
<button onClick="download()">downlaod</button>
</form>
</body>
</html>