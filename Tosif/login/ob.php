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


	$conn=mysqli_connect('192.168.100.22','root','admin','tosif') or  die("Connection failed: " . mysqli_connect_error());
	
	
	
 	/*ob_start('ob_gzhandler');
	
    $result = mysqli_query($conn, "select * from student");


	if ( $result) 
	{
		
			echo '<table border="1">';
    	//echo "Database created successfully";
		while($row=mysqli_fetch_array($result))
		{
			echo '<tr><td>'.$row[1]."\n</td></tr>";
		}
		echo '</table>';
	} 
	else 
	{
		echo "Error creating database: " . mysqli_error($conn);
	}
	flush();
	while ($row = mysqli_fetch_assoc($result)) {
		while($row = mysqli_fetch_array($result));

        extract($row);
		
        print "Some info A:" .$row['name'];
       
    }

	mysqli_close($conn);
	ob_start();
    $output = ob_get_contents();
    ob_end_clean();
	
    file_put_contents("employee.txt", $output);*/
	 $to      = 'jaiminh.patel@flydocs.aero';
	$subject = 'the subject';
	$message = 'hello';
	$headers = 'From: jaiminh.patel@flydocs.aero' . "\r\n" .
		'Reply-To: aiminh.patel@flydocs.aero' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	
	mail($to, $subject, $message, $headers);

?>