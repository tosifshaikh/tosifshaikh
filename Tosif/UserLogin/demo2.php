<?php 
include('conn.php');

	$sql="select * from member";
	$res=mysqli_query($conn,$sql);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<form method="post">
<table>
<?php /*?><?php
 while($row=mysqli_fetch_array($res))
	   { 
		 ?>
		 <tr align="center"><td><input type="checkbox" name="checkbox[]" value="<?php  echo $row[0]?> "></td><td><?php echo $row[1]?></td><td><?php echo $row[5]?></td><td><?php echo $row[6]?></td><td><?php echo $row[8]?></td></tr>
	<?php
	   }
    ?>
    <input type="submit" name="btndel" value="del">
    </table>
    <?php
	 if(isset($_POST['btndel']))
	 {
		 for($i=0;$i<count($_POST['checkbox']);$i++)
		 {
			 $del_id=$_POST['checkbox'][$i];
		 		$delq="delete from member where mem_id='$del_id'";
				mysqli_query($conn,$delq);
		 }
	 }
	?><?php */echo "hi"; ?>
    
   
</form>
</body>
</html>