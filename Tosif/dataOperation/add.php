<?php
include('conn.php');
if(isset($_POST['username']))
{
  $username=$_POST['username'];
}
if(isset($_POST['password']))
{
  $password=$_POST['password'];
}
if(isset($_POST['fname']))
{
  $fname=$_POST['fname'];
}
if(isset($_POST['hobbiesGroup']))
{
 $hobbiesGroup=implode(",",$_POST['hobbiesGroup']);
}
if(isset($_POST['lname']))
{
	$lname=$_POST['lname'];
}
if(isset($_POST['gender']))
{
	 $gender=$_POST['gender'];
}
if(isset($_POST['address']))
{
	$address=$_POST['address'];
}
if(isset($_POST['contact'])) 
{ 
 $contact=$_POST['contact'];
}
 if(!empty($_POST['action']))
 {
	 $action=$_POST['action'];
	
 }
  if($action=="insert")
  { 
    $insQuery=mysqli_prepare($conn,"INSERT INTO member(username,password,fname,lname,address,contact,gender,hobbies) VALUES(?,?,?,?,?,?,?,?)");
	mysqli_stmt_bind_param($insQuery,'ssssssss',$username,$password,$fname, $lname,$address,$contact,$gender,$hobbiesGroup);
	mysqli_stmt_execute($insQuery);
	//mysqli_query($conn,"INSERT INTO member(username,password,fname,lname,address,contact,gender,hobbies) VALUES('$username','$password','$fname', '$lname','$address','$contact','$gender','$hobbiesGroup')") or die(mysqli_error($conn));
	$memId=mysqli_insert_id($conn);
	//if(mysqli_affected_rows($conn)>0)
	if(mysqli_stmt_affected_rows($insQuery)>0)
	{
	   //$arr=array('id'=>$memId,'msg'=>'New Row Added');
	   echo $memId;
	}
 else
	   {
		  //$arr=array('id'=>0,'msg'=>'No Chanage');<br>
          echo 0;
	   } 
	  // echo json_encode($arr);
  }
	 mysqli_stmt_close($insQuery);
	 mysqli_close($conn);


?>