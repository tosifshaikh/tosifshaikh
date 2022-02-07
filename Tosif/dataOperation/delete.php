<?php
include('conn.php');

if(isset($_POST['action']))
{
	//echo $_POST['action'];
	$del_arr=array();
	foreach($_POST['selId'] as $delId)
	{
		//$delQuery="delete from member where mem_id=$delId";
		$delQuery=mysqli_prepare($conn,'DELETE from member WHERE mem_id=?');
		mysqli_stmt_bind_param($delQuery,'i',$delId);
		mysqli_stmt_execute($delQuery);
		
        //$result=mysqli_query($conn,$delQuery);
		array_push($del_arr,$delId);
	}
	//if(mysqli_affected_rows($conn)>0)
    if(mysqli_stmt_affected_rows($delQuery)>0)
   {
	 echo json_encode($del_arr);
   }
 else
 {
	 echo 0;
 }
  mysqli_stmt_close($delQuery);
}


 if(isset($_POST['memid']))
 {
	 $memid=$_POST['memid'];
	 //$delQuery="delete from member where mem_id=$memid";
	 $delQuery=mysqli_prepare($conn,'DELETE from member WHERE mem_id=?');
	 mysqli_stmt_bind_param($delQuery,'i',$memid);
	 mysqli_stmt_execute($delQuery);
 
// $result=mysqli_query($conn,$delQuery);
 //if(mysqli_affected_rows($conn)>0)
 if(mysqli_stmt_affected_rows($delQuery)>0)
 {
	 echo $memid;
 }
 else
 {
	 echo 0;
 }
  mysqli_stmt_close($delQuery);
  }
  
?>