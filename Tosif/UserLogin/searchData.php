<?php
session_start();
require_once('conn.php');


//if($_GET['flag']==3)
//{
	if(!empty($_GET['wrd']))
	{
	 $selQuery="select * from member where username like '".$_GET['wrd']."%'";
	 $result=mysqli_query($conn,$selQuery)or die(mysqli_error($conn)); 
	 $rowCount=mysqli_num_rows($result);
	// $_SESSION['flag']=3;  
	?>
	<tr><th>UserName</th><th>Address</th><th>Mobile</th><th>Gender</th></tr>
	 <?php 
	  if($rowCount==0)
	 {
		 echo "No Record Found";
	 }
	 else
	 {
		while($row=mysqli_fetch_array($result))
	   { 
		 ?>
		 <tr align="center"><td><?php echo $row[1]?></td><td><?php echo $row[5]?></td><td><?php echo $row[6]?></td><td><?php echo $row[8]?></td></tr>
	<?php	 
	  }
	  }
	 }
/*}
<?php ?>else if($_GET['flag']==1)
{
	 $sql="select * from member WHERE flg=0";
	$res=mysqli_query($conn,$sql);
	
?>
        
        <tr><th>#</th><th align="center">Id</th><th>UserName</th><th>Fname</th><th colspan="2">Action</th></tr>
       
	<?php
    while($row=mysqli_fetch_array($res))
    { ?>
      
    <tr align="center"><td><input type="checkbox" name="checkbox[]" value="<?php echo $row[0] ?>"></td><td ><?php echo $row[0] ?></td><td><?php echo $row[1] ?></td><td ><?php echo $row[3] ?></td><td colspan="2" align="center"><a href='searchData.php?Approve_id=<?php echo $row[0] ?>&flag=1' class="myButton2">Approve</a>
    
    </td></tr>
    <?php } ?>
    <tr><td align="center"><a href='Search.php?<?php echo $_SESSION['flag']='del'?>'><input type="submit" name="btnDel" value="Delete" ></a></td></tr>
    <!--</table>-->
    
    <?php
    if(isset($_GET['Approve_id']))
    {
        $Approve_id=$_GET['Approve_id'];
        $approveQuery="update member set flg=1 where mem_id='$Approve_id'";
        $result=mysqli_query($conn,$approveQuery);
        if($result)
		{
			//$_SESSION['flag']=1;	
			//$GLOBALS['flag']=2;
			header('location:Search.php');
		}
    }
	
	//if(isset($v)&&$v=="del")
//	{
//		
//		for($i=0;$i<count($_POST['checkbox']);$i++)
//		{
//			echo $_POST['checkbox'];
//			//$del_id=$_POST['checkbox'][$i];
////			$delQuery="delete from member where mem_id='$del_id'";
////			mysqli_query($conn,$delQuery);
//		}
//	}
   
}
else if($_GET['flag']==2)
{
		$sql="select * from member WHERE flg=1";
		$res=mysqli_query($conn,$sql);
	?>
		<tr><th align="center">Id</th><th>UserName</th><th>Fname</th><th colspan="2">Action</th></tr>
	<?php
	while($row=mysqli_fetch_array($res))
	{ ?>
	<tr align="center"><td ><?php echo $row[0] ?></td><td><?php echo $row[1] ?></td><td ><?php echo $row[3] ?></td><td colspan="2" align="center"><a href='searchData.php?reject_id=<?php echo $row[0] ?>&flag=2' class="myButton2">Reject</a>
	
	</td></tr>
	<?php } ?>
	<!--</table>-->
	
	<?php
	if(isset($_GET['reject_id']))
	{
		$reject_id=$_GET['reject_id'];
		$approveQuery="update member set flg=0 where mem_id='$reject_id'";
		$result=mysqli_query($conn,$approveQuery);
		if($result){
		//$_SESSION['flag']=2;	
		header('location:Search.php');
	}
	}
}<?php */?>
 
