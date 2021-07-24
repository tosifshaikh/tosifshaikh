<?php
include('conn.php');
if(isset($_POST['memId']))
{
 $memId=$_POST['memId'];
}
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
 if($action=="update")
 {
	//$updateQuery="update member set username='".$username."',
//	fname='".$fname."',lname='".$lname."',address='".$address."',contact='".$contact."',
//	gender='".$gender."',hobbies='".$hobbiesGroup."' where mem_id=$memId ";
  $updateQuery=mysqli_prepare($conn,'UPDATE member SET username=?,fname=?,lname=?,address=?,contact=?,gender=?,hobbies=? WHERE mem_id=?');
	
	mysqli_stmt_bind_param($updateQuery,'sssssssi',$username,$fname,$lname,$address,$contact,$gender,$hobbiesGroup,$memId);
	mysqli_stmt_execute($updateQuery);
	//mysqli_query($conn,$updateQuery) or die(mysqli_error($conn));
	//if(mysqli_affected_rows($conn)>0)
	if(mysqli_stmt_affected_rows($updateQuery)>0)
	{
	//$arr=array("id"=>$memId,"msg"=>"Row Updated");
	echo $memId;
	
   }
   else
   {
	   //$arr=array("id"=>0,"msg"=>" No Row Updated");
	   echo 0;
   }
 }
 mysqli_stmt_close($updateQuery);
// echo json_encode($arr);
 
?>