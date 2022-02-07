<?php
include('conn.php');
$selQuery=mysqli_prepare($conn,'SELECT * from member WHERE mem_id=? ORDER BY mem_id DESC');
	
	mysqli_stmt_bind_param($selQuery,'i',$mem_id);
	mysqli_stmt_execute($selQuery);
	$rowCount=mysqli_stmt_num_rows($selQuery);
	
	//$row=array();
	// Bind the result and retrieve the data
    $row[0] = "";
    //$email = "";
    mysqli_stmt_bind_result($selQuery, $row[0]);
    while (mysqli_stmt_fetch($selQuery)) {
        echo $row[0];
        //echo $email;
    }

    // And close the statement
    mysqli_stmt_close($selQuery);
	
?>